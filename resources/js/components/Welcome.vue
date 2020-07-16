<template>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                Product
                <p class="sub-title">View all our products by clicking on the button below</p>
            </div>
            <a v-bind:href="product_page" class="btn btn-primary">Our Products</a>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            product_page: "/products"
        };
    }
};
</script>
