<template>
    <div class="m-1 rounded-3 p-2"  style="background-color:rgba(17, 17, 17, 0.05);">
        <div class="image" style="align-items:center; display:flex;" >
            <img style="height:50px;" :src="review.profile_photo_url" class="img-circle elevation-2" alt="User Image">
            <label class="px-2 text-nowrap google-fonts">
                <label v-text="review.author_name" style="font-weight:bold;"></label>
                <small class="d-block" style="font-size:1em; color:gray;" v-text="review.relative_time_description"> </small>
            </label>
        </div>
        <div>
            <div class="rate px-5" style="align-items:center; display:flex;">
                <template v-for="index in 5">
                    <img v-if="index <= review.rating" v-bind:key="'review-star-'+index" src="/images/components/star.svg" style="height: 15px;"/>
                    <img v-else v-bind:key="'review-star-'+index" src="/images/components/star-gray.svg" style="height: 15px;"/>
                </template>
            </div>
        </div>
        <div>
            <label class="google-fonts" v-text="review.text" style="text-align:justify;"></label>
        </div>
        <div class="my-3">
            <a :href="review.author_url" target="_blank" style="display:flex; align-items:center;text-decoration:none;">
                <img src="/images/components/google-color-logo.svg" style="height: 40px;" />
                <label class="google-fonts">
                    <small class="d-block" style="font-size:0.8em; color:gray; cursor:pointer;"> Posted on </small>
                    <span>Google</span>
                </label>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ "review" ],
        components: { 
        },
        data: function () {
            return {    
            }
        },
        methods: { 

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
