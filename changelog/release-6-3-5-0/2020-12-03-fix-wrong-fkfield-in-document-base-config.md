---
title: Fix wrong FkField in DocumentBaseConfig
issue: NEXT-12565
author: Max Stegmeyer
author_email: m.stegmeyer@snapadmin.net
author_github: @mstegmeyer
---
# Core
* Changed `FkField` for `logoId` from `DocumentTypeDefinition` to `MediaDefinition` in `DocumentBaseConfigDefinition`.
