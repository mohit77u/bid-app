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
                <h3>{{ remaining.min }} : {{ remaining.seconds }}</h3>
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
                            <td :class="result.result_color"><p class="number">{{ result.result_number}}</p></td>
                            <td :class="result.result_color"><div class="color"></div></td>
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
            type: '',
            value: '',
            qty: 1,
            amountValue: 10,
            totalAmount: 10,
            period: '',
            results: {},
            remaining: {
                min: '02',
                seconds: '59',
            },
            expiredTime: ''
        }
    },
    methods: {
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
            const order = {
                type: this.type,
                value: this.value,
                amount: this.totalAmount,
                period: this.period,
            }
            console.log(order)
            await axios.post('/order-create', order)
                .then(res => {
                    console.log(res)
                })
            
        },
        Cancel() {
            this.popup = false
            Object.assign(this.$data, this.$options.data())
        },
        postResults() {
            axios.post('/results')
                .then(res => {
                    let currentGame = res.data.current_game
                    this.period = currentGame.period
                    this.expiredTime = currentGame.expired_time
                })
        },
        getAllResults() {
            axios.get('/results?page=1')
                .then(res => {
                    let result = res.data.data
                    this.results = result.data
                    this.period = result.data[0].period + 1
                    
                })
        },
        updateTimer() {
            setTimeout(() => {
                const future = Date.parse(this.expiredTime);
                var now = new Date();
                console.log(now)
                var diff = future - now;

                var hours = Math.floor(diff / (1000 * 60 * 60));
                var mins = Math.floor(diff / (1000 * 60));
                var secs = Math.floor(diff / 1000);

                this.remaining.min = '0' + mins - hours * 60;
                this.remaining.min = '0' + this.remaining.min;
                if(secs - mins * 60 < 10){
                    this.remaining.seconds = secs - mins * 60;
                    this.remaining.seconds = '0' + this.remaining.seconds;
                } else {
                    this.remaining.seconds = secs - mins * 60;
                }
                this.updateTimer()

                if (this.remaining.min === '00' && this.remaining.seconds === '00') {
                    this.loading = true
                    setTimeout(() => {
                        this.loading = false
                        this.postResults()
                        this.getAllResults()
                    }, 5000)
                }
            }, 1000)

        },
        getCurrentGame(){
            axios.get('/current-game')
            .then(res=>{
                console.log(res.data)
                let currentGame = res.data.current_game
                this.period = currentGame.period
                this.expiredTime = currentGame.expired_time
            })
        }
    },
    created() {
        this.updateTimer()
        this.getAllResults()
        this.getCurrentGame()
    },
}
</script>
