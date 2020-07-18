<template>
    <div class="container">
        <nav class="nav justify-content-end">
            <div class="top-right links m-1">
                <a v-bind:href="main_page">Main Page</a>
            </div>
        </nav>
        <div class="d-flex justify-content-center align-items-center flex-column m-5">
            <div class="title-2">Transactions</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="transaction in transactions" :key="transaction.id">
                        <th>{{ transaction.id }}</th>
                        <td>{{ transaction.email }}</td>
                        <td>+{{ transaction.contact }}</td>
                        <td
                            v-if="transaction.transaction_status == 'Success'"
                            class="status-success"
                        >{{ transaction.transaction_status }}</td>
                        <td
                            v-else-if="transaction.transaction_status == 'Refund'"
                            class="status-refund"
                        >{{ transaction.transaction_status }}</td>
                        <td v-else class="status-failed">{{ transaction.transaction_status }}</td>
                        <td>
                            <button
                                @click="showTransaction(transaction)"
                                class="btn btn-primary"
                            >Details</button>
                        </td>
                        <td v-if="transaction.transaction_status == 'Success'">
                            <a
                                v-bind:href="'payment/' + transaction.id + '/refund/'+ transaction.razorpay_payment_id"
                                class="btn btn-primary"
                            >Refund</a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                        <a
                            class="page-link"
                            href="#"
                            @click="fetchTransactions(pagination.prev_page_url)"
                        >Previous</a>
                    </li>
                    <li class="page-item disabled">
                        <a
                            class="page-link text-dark"
                            href="#"
                        >Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                    </li>
                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                        <a
                            class="page-link"
                            href="#"
                            @click="fetchTransactions(pagination.next_page_url)"
                        >Next</a>
                    </li>
                </ul>
            </nav>

            <div v-if="transaction.id" class="float-right">
                <p class="sub-title">Transaction Details</p>
                <p
                    v-if="transaction.transaction_status == 'Success'"
                    class="status-success"
                >{{ transaction.transaction_status }}</p>
                <p
                    v-else-if="transaction.transaction_status == 'Refund'"
                    class="status-refund"
                >{{ transaction.transaction_status }}</p>
                <p v-else class="status-failed">{{ transaction.transaction_status }}</p>
                <p>
                    <strong>Email:</strong>
                    {{ transaction.email }}
                </p>
                <p>
                    <strong>Contact:</strong>
                    <span>+{{ transaction.contact }}</span>
                </p>
                <p>
                    <strong>Product Name:</strong>
                    {{ transaction.product_name }}
                </p>
                <p>
                    <strong>Product Price:</strong>
                    <span>{{ transaction.product_price }} ₹</span>
                </p>
                <p>
                    <strong>Razorpay Payment Id:</strong>
                    {{ transaction.razorpay_payment_id }}
                </p>
                <p>
                    <strong>Razorpay Order Id:</strong>
                    {{ transaction.razorpay_order_id }}
                </p>
                <div v-if="transaction.transaction_status == 'Failed'">
                    <p class="sub-title">Error Details</p>
                    <p>
                        <strong>Code:</strong>
                        {{ transaction.error.code }}
                    </p>
                    <p>
                        <strong>Description:</strong>
                        {{ transaction.error.description }}
                    </p>
                    <p>
                        <strong>Source:</strong>
                        {{ transaction.error.source }}
                    </p>
                    <p>
                        <strong>Reason:</strong>
                        {{ transaction.error.reason }}
                    </p>
                </div>
                <div v-if="transaction.transaction_status == 'Refund'">
                    <p class="sub-title">Refund Details</p>
                    <p>
                        <strong>Razorpay Refund Id:</strong>
                        {{ transaction.razorpay_refund_id }}
                    </p>
                    <p>
                        <strong>Razorpay Refund Status:</strong>
                        {{ transaction.razorpay_refund_status }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_page: "/",
            transactions: [],
            transaction: {
                id: "",
                transaction_status: "",
                email: "",
                contact: "",
                product_name: "",
                product_price: "",
                razorpay_payment_id: "",
                razorpay_order_id: "",
                razorpay_refund_id: "",
                razorpay_refund_status: "",
                error: ""
            },
            transaction_id: "",
            pagination: {}
        };
    },
    created() {
        //  get all transactions
        this.fetchTransactions();
    },
    methods: {
        //  Transaction data handling
        fetchTransactions(page_url) {
            let vm = this;
            page_url = page_url || "api/transactions";
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.transactions = res.data;
                    vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
        },
        makePagination: function(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            };
            this.pagination = pagination;
        },
        showTransaction(transaction) {
            this.transaction.id = transaction.id;
            this.transaction.transaction_id = transaction.id;
            this.transaction.transaction_status =
                transaction.transaction_status;
            this.transaction.email = transaction.email;
            this.transaction.contact = transaction.contact;
            this.transaction.product_name = transaction.product_name;
            this.transaction.product_price = transaction.product_price;
            this.transaction.razorpay_payment_id =
                transaction.razorpay_payment_id;
            this.transaction.razorpay_order_id = transaction.razorpay_order_id;
            this.transaction.razorpay_refund_id =
                transaction.razorpay_refund_id;
            this.transaction.razorpay_refund_status =
                transaction.razorpay_refund_status;
            this.transaction.error = transaction.error;
        }
    }
};
</script>
