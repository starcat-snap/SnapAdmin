#!/usr/bin/env sh
set -eu

if [ -n "${DEBUG:-}" ]; then
    set -x
fi

PLATFORM_DIR="${CI_PROJECT_DIR:-$(pwd)}"

# Transforms input into lowercase-only.
#
# [1]: A string
lowercase() {
  echo "${1}" | tr '[:upper:]' '[:lower:]'
}

# Creates a split repository for a subpackage of platform.
#
# [1]: A subpackage. e.g.: "Administration"
split_repo() {
  local package="${1}"
  local package_lower=$(lowercase "${package}")

  local split_repos_dir="${PLATFORM_DIR}/repos"
  local split_repo_dir="${PLATFORM_DIR}/repos/$(lowercase ${package})"
  local tmp_target_repo_dir="$(mktemp -d)/"
  local default_branch="$(git config --global init.defaultBranch)"; default_branch=${default_branch:-trunk}

  git config --global --add safe.directory "${PLATFORM_DIR}" # TODO: Find out why this is necessary in CI.

  mkdir -p "${split_repos_dir}"

  git -C "${PLATFORM_DIR}" subtree split -q -P src/${package}/  -b ${package_lower}

  git -C "${PLATFORM_DIR}" remote remove "tmp_target_repo" > /dev/null 2>&1 || true

  git init -b "${default_branch}" --bare "${tmp_target_repo_dir}"

  git -C "${PLATFORM_DIR}" remote add -t "${default_branch}" "tmp_target_repo" "${tmp_target_repo_dir}"
  git -C "${PLATFORM_DIR}" push -u "tmp_target_repo" "${package_lower}:${default_branch}" -f

  if [ -d "${split_repo_dir}" ]; then
    local scrapyard="$(mktemp -d)"

    printf "INFO: Directory %s already exists, moving it out of the way to %s...\n" "${split_repo_dir}" "${scrapyard}"
    mv "${split_repo_dir}" "${scrapyard}"
  fi

  git clone -b "${default_branch}" "${tmp_target_repo_dir}" "${split_repo_dir}"
}

# Copies existing assets for a subpackage of platform into the respective split
# repositories.
#
# [1]: A subpackage. e.g.: "Administration"
copy_assets() {
  local package="${1}"
  local package_lower=$(lowercase "${package}")

  if [ -d "${PLATFORM_DIR}/src/${package}/Resources/public" ]; then
    cp -r "${PLATFORM_DIR}/src/${package}/Resources/public" "${PLATFORM_DIR}/repos/${package_lower}/Resources/"
  fi

  if [ -d "${PLATFORM_DIR}/src/${package}/Resources/app/${package_lower}/dist" ]; then
    cp -r "${PLATFORM_DIR}/src/${package}/Resources/app/${package_lower}/dist" "${PLATFORM_DIR}/repos/${package_lower}/Resources/app/${package_lower}/"
  fi

  if [ -d "${PLATFORM_DIR}/src/${package}/Resources/app/${package_lower}/vendor" ]; then
    cp -r "${PLATFORM_DIR}/src/${package}/Resources/app/${package_lower}/vendor" "${PLATFORM_DIR}/repos/${package_lower}/Resources/app/${package_lower}/"
  fi
}

# Returns a list of mandatory assets for the Administration package.
admin_assets_list() {
  cat <<EOF | tr -d '[:blank:]'
    ${PLATFORM_DIR}/repos/administration/Resources/public/static/js/app.js
    ${PLATFORM_DIR}/repos/administration/Resources/public/static/css/app.css
EOF
}


# Checks whether all mandatory assets have been generated and copied to the
# correct repository.
check_assets() {
  stat -t $(admin_assets_list) > /dev/null
}

# Removes certain asset-related entries from the admin .gitignore.
include_admin_assets() {
  sed -E '/[/]?public([/]?|.*)/d' "${PLATFORM_DIR}/repos/administration/Resources/.gitignore" > "${PLATFORM_DIR}/repos/administration/Resources/.gitignore"
}

require_core_version() {
  local package="${1}"
  local package_lower=$(lowercase "${package}")
  local version="${2}"

  if [ "${package_lower}" != "core" ]; then
    composer -d "${PLATFORM_DIR}/repos/${package_lower}" require "58shuwei/core:${version}" --no-update --no-install
  fi
}

# Creates a new branch pointing at the current HEAD in a split repository of a
# subpackage of platform.
#
# [1]: A subpackage. e.g.: "Administration"
# [2]: The branch name.
branch() {
  local package="${1}"
  local package_lower=$(lowercase "${package}")
  local name="${2}"
  local commit_id=$(git -C "${PLATFORM_DIR}/repos/${package_lower}" log -n1 --format="%H")

  git -C "${PLATFORM_DIR}/repos/${package_lower}" branch "${name}" "${commit_id}"
  git -C "${PLATFORM_DIR}/repos/${package_lower}" checkout "${name}"
}

# Commits additional files in a split repository of a subpackage of platform.
#
# [1]: A subpackage. e.g.: "Administration"
# [2]: A commit message.
commit() {
  local package="${1}"
  local package_lower=$(lowercase "${package}")
  local message="${2}"

  git -C "${PLATFORM_DIR}/repos/${package_lower}" add .
  git -C "${PLATFORM_DIR}/repos/${package_lower}" commit --allow-empty -m "${message}"
}

# Creates a tag in a split repository of a subpackage of platform.
#
# [1]: A subpackage. e.g.: "Administration"
# [2]: The tag name.
tag() {
  local package="${1}"
  local package_lower=$(lowercase "${package}")
  local name="${2}"

  git -C "${PLATFORM_DIR}/repos/${package_lower}" tag -m "Release ${name}" "${name}" -f
}

# Pushes a split repository for a subpackage to it's remote.
#
# [1]: A subpackage. e.g.: "Administration"
# [2]: Base-URL of the remote repository, e.g.: "https://user:pass@git.example.com"
# [3]: The ref to push to, e.g.: "6.4.20.0"
push() {
  local package="${1}"
  local package_lower=$(lowercase "${package}")
  local remote_base_url="${2}"
  local target_ref="${3}"

  local remote_url=$(printf "%s/%s.git" "${remote_base_url}" "${package_lower}")

  git -C "${PLATFORM_DIR}/repos/${package_lower}" remote remove upstream > /dev/null 2>&1 || true
  git -C "${PLATFORM_DIR}/repos/${package_lower}" remote add upstream "${remote_url}"

  git -C "${PLATFORM_DIR}/repos/${package_lower}" fetch -q upstream

  if git -C "${PLATFORM_DIR}/repos/${package_lower}" show-ref --verify "refs/tags/${target_ref}" > /dev/null 2>&1 ; then
    git -C "${PLATFORM_DIR}/repos/${package_lower}" push upstream "refs/tags/${target_ref}:refs/tags/${target_ref}" -f
  else
    git -C "${PLATFORM_DIR}/repos/${package_lower}" push upstream "refs/heads/${target_ref}:refs/heads/${target_ref}" -f
  fi
}

push_tag() {
  local package="${1}"
  local package_lower=$(lowercase "${package}")
  local remote_base_url="${2}"
  local target_ref="${3}"

  local remote_url=$(printf "%s/%s.git" "${remote_base_url}" "${package_lower}")

  git -C "${PLATFORM_DIR}/repos/${package_lower}" remote remove upstream > /dev/null 2>&1 || true
  git -C "${PLATFORM_DIR}/repos/${package_lower}" remote add upstream "${remote_url}"

  git -C "${PLATFORM_DIR}/repos/${package_lower}" fetch -q upstream

  git -C "${PLATFORM_DIR}/repos/${package_lower}" push upstream "refs/tags/${target_ref}:refs/tags/${target_ref}" -f

}

"$@"
