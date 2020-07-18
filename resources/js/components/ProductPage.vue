<template>
    <div
        class="d-flex justify-content-center align-items-center flex-column position-ref full-height"
    >
        <nav class="nav justify-content-end">
            <div class="top-right links m-1">
                <a v-bind:href="main_page">Main Page</a>
            </div>
        </nav>
        <div class="title m-b-md">Our Products</div>
        <div class="content d-flex align-items-center">
            <div v-for="product in products" :key="product.id">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">{{ product.name }}</h5>
                        <p class="card-text">{{ product.description }}</p>
                        <p>Price: {{ product.price }} ₹</p>
                        <a
                            v-bind:href="purchase_page + '/' + product.id"
                            class="btn btn-primary"
                            >Buy Now</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PurchasePage from "./PurchasePage.vue";

export default {
    data() {
        return {
            main_page: "/",
            purchase_page: "purchase",
            products: [
                {
                    id: "1",
                    name: "Product 1",
                    description: "Product 1 Description",
                    price: "10"
                },
                {
                    id: "2",
                    name: "Product 2",
                    description: "Product 2 Description",
                    price: "20"
                },
                {
                    id: "3",
                    name: "Product 3",
                    description: "Product 3 Description",
                    price: "30"
                }
            ]
        };
    }
};
</script>
