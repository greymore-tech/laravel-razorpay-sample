<template>
    <div class="container">
        <nav class="nav justify-content-end">
            <div class="top-right links m-1">
                <a v-bind:href="product_page">Products Page</a>
            </div>
        </nav>
        <div class="row no-gutters">
            <div class="col-sm-6 col-md-12">
                <div
                    class="d-flex justify-content-center align-items-center flex-column position-ref full-height"
                >
                    <div class="title-2">Purchase Details</div>
                    <div class="content w-50">
                        <form method="POST" action="https://api.razorpay.com/v1/checkout/embedded">
                            <input
                                type="hidden"
                                name="_token"
                                value="YzXAnwBñC7qPK9kg7MGGIUzznEOCi2dTnG9h9çpB"
                            />
                            <div class="form-group">
                                <label for="name" class="d-flex justify-content-start">Name</label>
                                <input
                                    type="text"
                                    name="prefill[name]"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <label for="email" class="d-flex justify-content-start">Email</label>
                                <input
                                    type="email"
                                    name="prefill[email]"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <label for="mobile" class="d-flex justify-content-start">Mobile</label>
                                <input
                                    type="number"
                                    name="prefill[contact]"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div v-for="product in products" :key="product.id">
                                <div v-if="product.id == id">
                                    <div class="form-group">
                                        <label
                                            for="product_name"
                                            class="d-flex justify-content-start"
                                        >Product Name</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            :value="product.name"
                                            readonly
                                            required
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="product_price"
                                            class="d-flex justify-content-start"
                                        >Product Price</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            :value="product.price"
                                            readonly
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="key_id" :value="razorpay_key" />
                            <input type="hidden" name="order_id" :value="order_id" />
                            <input type="hidden" name="name" value="Demo Purchase Page" />
                            <input
                                type="hidden"
                                name="callback_url"
                                :value="callback_url + product_id"
                            />

                            <input type="hidden" name="cancel_url" :value="cancel_url" />
                            <button
                                type="submit"
                                class="btn btn-primary btn-block"
                            >Proceed For Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
var id = window.location.href.split("/").pop();
var base_url = window.location.origin;

export default {
    props: [
        "razorpay_key",
        "order_id",
        "product_id",
        "product_name",
        "product_price"
    ],
    data() {
        return {
            id: id,
            callback_url: base_url + "/payment/verify/",
            cancel_url: base_url + "/products",
            product_page: "/products",
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
