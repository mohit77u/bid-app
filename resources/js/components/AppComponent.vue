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
                    <h3>{{ timerCount }}</h3>
                </div>

                <div class="color-group my-4">
                    <button class="btn btn-success"  @click="setOrder('color', 'green')">Join Green</button>
                    <button class="btn btn-purple"  @click="setOrder('color', 'voilet')">Join Voilet</button>
                    <button class="btn btn-danger"  @click="setOrder('color', 'red')">Join Red</button>
                </div>

                <div class="number-group">
                    <button class="single-number btn btn-primary" v-for="number in 10" :key="number" @click="setOrder('number', number-1)" >{{ number-1 }}</button>
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
                                    <button type="button" class="btn btn-primary qty-btn"  @click="increaseQty">+</button>
                                </div>
                            </div>
                            <p class="my-4">Total contract money is {{ totalAmount }}</p>
                            <div class="bottom-buttons">
                                <button class="btn" @click="popup = !popup">Cancel</button>
                                <button class="btn confirm" @click="SubmitBidData">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'AppComponent',
        data(){
            return{
                popup: false,
                disabled: false,
                timerCount: 60,
                type: '',
                value: '',
                qty: 1,
                amountValue: 10,
                totalAmount: 10,
            }
        },
        watch: {
            timerCount: {
                handler(value) {
                    if (value > 0) {
                        setTimeout(() => {
                            this.timerCount--;
                        }, 1000);
                    }
                    if(value <= 30){
                        this.disabled = true
                    }
                },
                immediate: true // This ensures the watcher is triggered upon creation
            }
        },
        methods:{
            setOrder(type, value){
                this.type = type
                this.value = value
                this.popup = true
            },
            setAmount(value){
                this.amountValue = value 
                this.totalAmount = this.amountValue * this.qty
            },
            decreaseQty(){
                if(this.qty >= 2){
                    this.qty = this.qty-1
                } else {
                    this.qty = 1
                }

                this.totalAmount = this.amountValue * this.qty
            },
            increaseQty(){
                if(this.qty <= 9){
                    this.qty = this.qty+1
                } else {
                    this.qty = 10
                }

                this.totalAmount = this.amountValue * this.qty
            },
            SubmitBidData(){
                console.log(this.totalAmount)
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
