<template>
<div>
    <section class="home py-2">
        <div class="container-fluid">
            <div class="box">
                <h4>Available balance: ₹ 0.00</h4>
                <div class="buttons mt-3">
                    <button class="btn btn-primary me-3">Recharge</button>
                    <button class="btn btn-light">Trend</button>
                </div>
            </div>

            <div class="period-timer">
                <h3>Period: {{ period }}</h3>
                <h3>{{ timerCount }}</h3>
            </div>

            <div class="color-group my-4">
                <button class="btn btn-success" @click="setOrder('color', 'green')">Join Green</button>
                <button class="btn btn-purple" @click="setOrder('color', 'voilet')">Join Voilet</button>
                <button class="btn btn-danger" @click="setOrder('color', 'red')">Join Red</button>
            </div>

            <div class="number-group">
                <button class="single-number btn btn-primary" v-for="number in 10" :key="number" @click="setOrder('number', number-1)">{{ number-1 }}</button>
            </div>

            <div class="popup" v-if="popup">
                <div class="popup-content">
                    <div :class="[type === 'color' ? 'popup-header-green' : 'popup-header-blue', 'popup-header']">
                        <h4 v-if="type === 'color'">Join {{ value }}</h4>
                        <h4 v-else>Select {{ value }}</h4>
                    </div>
                    <div class="popup-main">
                        <h5>Contract Money</h5>
                        <div class="amount btn-group" role="group">
                            <button type="button" class="btn btn-primary" @click="setAmount(10)">10</button>
                            <button type="button" class="btn btn-primary" @click="setAmount(100)">100</button>
                            <button type="button" class="btn btn-primary" @click="setAmount(1000)">1000</button>
                            <button type="button" class="btn btn-primary" @click="setAmount(10000)">10000</button>
                        </div>
                        <div class="number my-4">
                            <h5>Number</h5>
                            <div class="calculate-amount btn-group mt-4" role="group">
                                <button type="button" class="btn btn-primary qty-btn" @click="decreaseQty">-</button>
                                <p>{{ qty }}</p>
                                <button type="button" class="btn btn-primary qty-btn" @click="increaseQty">+</button>
                            </div>
                        </div>
                        <p class="my-4">Total contract money is {{ totalAmount }}</p>
                        <div class="bottom-buttons">
                            <button class="btn" @click="Cancel">Cancel</button>
                            <button class="btn confirm" @click="SubmitBidData">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="results my-5">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Period</th>
                            <th scope="col">Number</th>
                            <th scope="col">Color</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(result,index) in results" :key="index">
                            <th scope="row">{{index + 1}}</th>
                            <td>{{ result.period}}</td>
                            <td>{{ result.result_number}}</td>
                            <td>{{ result.result_color}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="loading">
                <div class="showbox">
                    <div class="loader">
                        <svg class="circular" viewBox="25 25 50 50">
                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AppComponent',
    data() {
        return {
            popup: false,
            loading: false,
            timerCount: 30,
            type: '',
            value: '',
            qty: 1,
            amountValue: 10,
            totalAmount: 10,
            period: '',
            results: {},
        }
    },
    watch: {
        timerCount: {
            handler(value) {
                if (value > 0) {
                    setTimeout(() => {
                        this.timerCount--;
                    }, 1000);
                } else if (value === 0) {
                    this.loading = true
                    setTimeout(() => {
                        this.loading = false
                        this.clearInterval()
                        this.postResults()
                        this.getAllResults()
                    }, 5000)
                }
            },
            immediate: true // This ensures the watcher is triggered upon creation
        }
    },
    methods: {
        clearInterval() {
            this.timerCount = 30
        },
        setOrder(type, value) {
            this.type = type
            this.value = value
            this.popup = true
        },
        setAmount(value) {
            this.amountValue = value
            this.totalAmount = this.amountValue * this.qty
        },
        decreaseQty() {
            if (this.qty >= 2) {
                this.qty = this.qty - 1
            } else {
                this.qty = 1
            }

            this.totalAmount = this.amountValue * this.qty
        },
        increaseQty() {
            if (this.qty <= 9) {
                this.qty = this.qty + 1
            } else {
                this.qty = 10
            }

            this.totalAmount = this.amountValue * this.qty
        },
        async SubmitBidData() {
            this.popup = false
            const data = {
                type: this.type,
                value: this.value,
                amount: this.totalAmount,
            }
            console.log(data)
            await axios.post('/order-create', data)
                .then(res => {
                    console.log(res)

                })
            Object.assign(this.$data, this.$options.data())
        },
        Cancel() {
            this.popup = false
            Object.assign(this.$data, this.$options.data())
        },
        postResults() {
            axios.post('/results')
                .then(res => {
                    console.log(res.data)
                    let result = res.data.data
                    this.period = result.period + 1
                })
        },
        getAllResults() {
            axios.get('/results?page=1')
                .then(res => {
                    console.log(res.data)
                    let result = res.data.data
                    console.log(result)
                    this.results = result.data
                    this.period = result.data[0].period + 1
                })
        }
    },
    created() {
        // this.postResults()
    },
    beforeMount(){
        this.getAllResults()
    },
}
</script>
