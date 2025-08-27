<template>
    <div v-if="reviewList" >
        <div class="row px-3" style="color:black;"> 
            <div v-for="(item, index) in reviewList.slice(0,3)" v-bind:key="'review-item-'+index" :class="'py-2 col-sm-4'" >
                <google-customer-review-item :review="item"></google-customer-review-item>
            </div> 
        </div>
        <div v-if="reviewList.length == 5" class="row px-3" style="color:black;">
            <div class="col-sm-2"></div>
            <div v-for="(item, index) in reviewList.slice(3,5)" v-bind:key="'review-item2-'+index" :class="'py-2 col-sm-4'">
                <google-customer-review-item :review="item"></google-customer-review-item>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</template>

<script>
    import GoogleCustomerReviewItemVue from './GoogleCustomerReviewItem.vue';
    export default {
        props:[ "reviews" ],
        components: { 
            "google-customer-review-item":GoogleCustomerReviewItemVue
        },
        data: function () {
            return {    
                reviewList:null
            }
        },
        methods: { 

        },
        mounted:function(){    
            //console.log(this.reviews); 
            this.reviewList = this.reviews;//.slice(0,3);
            //console.log(this.reviewList);
        },
        updated:function(){

        }
    }
</script>
