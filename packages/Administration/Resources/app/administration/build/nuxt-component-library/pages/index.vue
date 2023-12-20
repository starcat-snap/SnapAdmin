<template>
    <div class="page--homepage">
        <h1>Welcome to the SnapAdmin Component library</h1>

        <p class="is--xl">
            Explore the look and feel of components to speed up your app development. The SnapAdmin Administration is
            build with reusable components which you can use to build your own module
        </p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            title: 'Homepage'
        };
    },

    head() {
        return {
            title: this.title
        };
    }
}
</script>
