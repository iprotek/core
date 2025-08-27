<template> 
    <div class="row p-2">
        <div class="col-sm-6 p-2" align="center">
            <div class="text-nowrap" style="max-width:200px; display:flex;">
                <img src="/images/components/google.svg" style="width:80%;"/>
                <span class="google-fonts p-2" style='color:black; font-size: 1.7em;'>
                    Rating
                </span>
            </div>
            <a :href="review_full_link ? review_full_link : '' " target="_blank" style="text-decoration:none;">
                <div style="display:flex; align-items:center; justify-content:center;">
                <b> <label class="google-fonts m-0 pt-1" v-if="rating" v-text="rating" style='color:black; font-size: 1.5em;'></label> </b>
                    &nbsp;&nbsp;
                    <img src="/images/components/star.svg" style="height: 20px;"/>
                    <img src="/images/components/star.svg" style="height: 20px;"/>
                    <img src="/images/components/star.svg" style="height: 20px;"/>
                    <img src="/images/components/star.svg" style="height: 20px;"/>
                    <img src="/images/components/star.svg" style="height: 20px;"/>
                    <!--<img src="/images/components/star-gray.svg" style="height: 20px;"/>-->
                    &nbsp;&nbsp;
                    <label class="m-0 p-1 text-nowrap" v-text="' '+(user_ratings_total ? user_ratings_total : 'N/A' )+' reviews'" style="color:gray; cursor:pointer;"></label>
                </div>
            </a>
        </div>
        <div class="col-sm-6" style="align-items:center; display:flex; justify-content:center;">
            <a :href="(review_link ? review_link:'#')" class="btn btn-primary btn-lg text-nowrap"> Write a Review </a>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ "user_ratings_total","rating","review_link", "review_full_link" ],
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
