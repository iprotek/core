<template>
    <div class="row">
        <div class="col-sm-12 p2" :style="'display:'+(has_map === false ?'none':'')+';'">            
            <div :id="mapId" style="min-height: 300px;">
            <!-- Reviews will be displayed here -->
            </div>
        </div>
        <div class="col-sm-12 p-2"  :style="'display:'+(has_review === false ?'none':'')+';'">           
            <div :id="mapReviewId">
            <!-- Reviews will be displayed here -->
                
                <div class="card m-1 p-1" style="background-color:white;">
                    <div style="background-color:rgba(17, 17, 17, 0.05);">
                        <summary-review :review_full_link="review_full_link" v-if="placeService" :rating="placeService.rating" :user_ratings_total="placeService.user_ratings_total" :review_link="review_link"></summary-review>
                    </div>
                </div>
                <div v-if="placeService && placeService.reviews.length > 0" class="card m-1 p-1" style="background-color:white;">
                    <div>
                        <google-customer-reviews :reviews="placeService.reviews"></google-customer-reviews>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import GoogleSummaryReviewVue from './GoogleSummaryReview.vue'; 
    import GoogleCustomerReviewsVue from './GoogleCustomerReviews.vue';
    export default {
        props:[ "has_map", "has_review", "place_id", "place_lat", "place_lng", "zoom", "test","review_full_link","review_link"  ],
        components: { 
            "summary-review": GoogleSummaryReviewVue,
            "google-customer-reviews" : GoogleCustomerReviewsVue
        },
        data: function () {
            return {  
                mapId: 'google-reviews-widget-'+this._uid,
                mapReviewId: 'google-map-review-'+this._uid,  
                map:null,
                placeService:null
            }
        },
        methods: {
            
            loadMapDetails:function(){
                if(this.test === true){
                    this.placeService = this.testData();
                }
                else{
                    this.loadMap(); 
                    this.loadDetails(); 
                } 
                console.log(this.placeService);
            },
            loadMap:function(){
                //lat - 10.26386912939084
                //lng - 123.97213877090185
                //zoom = 13 
                var vm = this;
                var map = new google.maps.Map(document.getElementById(vm.mapId), {
                    center: { lat: vm.place_lat, lng: vm.place_lng }, // Replace with your business's coordinates
                    zoom: vm.zoom ? vm.zoom :13 // Adjust the zoom level as needed
                });
                vm.map = map; 
            },
            loadDetails:function(){
                //place_id - ChIJlTHQK16bqTMRsHhv74V3dWU
                var vm = this;
                var service = new google.maps.places.PlacesService(vm.map);  
                service.getDetails(
                    {
                        placeId: vm.place_id, // Replace with your business's Place ID from step 2
                        fields: ['formatted_address', 'website', 'opening_hours', 'price_level', 'rating', 'reviews', 'user_ratings_total', 'editorial_summary'],
                        //min_rating:1,
                        reviews_sort:'newest'
                    },
                    function (place, status) {
                        //console.log("GOOLE MAP",place, status);
                        if (status === google.maps.places.PlacesServiceStatus.OK) { 
                            var reviews = place.reviews; 
                            vm.placeService = place;
                            /*
                            var reviewsWidget = document.getElementById(vm.mapReviewId);
                            reviews.forEach(function (review) {
                                var reviewDiv = document.createElement('div');
                                reviewDiv.innerHTML = '<strong>' + review.author_name + '</strong><br>' + review.text;
                                reviewsWidget.appendChild(reviewDiv);
                            });
                            */
                        }
                    }
                );
            },
            testData:function(){
                return {
                        "formatted_address": "445 Suba Basbas Rd, Lapu-Lapu City, Cebu, Philippines",
                        "opening_hours": {
                            "periods": [
                            {
                                "open": {
                                "day": 0,
                                "time": "0000",
                                "hours": 0,
                                "minutes": 0
                                }
                            }
                            ],
                            "weekday_text": [
                            "Monday: Open 24 hours",
                            "Tuesday: Open 24 hours",
                            "Wednesday: Open 24 hours",
                            "Thursday: Open 24 hours",
                            "Friday: Open 24 hours",
                            "Saturday: Open 24 hours",
                            "Sunday: Open 24 hours"
                            ]
                        },
                        "rating": 4.8,
                        "reviews": [
                            {
                            "author_name": "Jed Chu",
                            "author_url": "https://www.google.com/maps/contrib/103214845950591904524/reviews",
                            "language": "en",
                            "profile_photo_url": "https://lh3.googleusercontent.com/a/ACg8ocJywJhJ3Nxx-Zr5XLrZ5QCa81tjkAlaaOKKo64w1YMV=s128-c0x00000000-cc-rp-mo",
                            "rating": 5,
                            "relative_time_description": "4 months ago",
                            "text": "I recently stayed at this hotel and was blown away by the exceptional service and attention to detail. The rooms were beautifully designed with stunning views, and the amenities were top-notch. The staff went above and beyond to ensure my stay was comfortable and memorable. Highly recommend!",
                            "time": 1682165469
                            },
                            {
                            "author_name": "Kapopoy Channel",
                            "author_url": "https://www.google.com/maps/contrib/104156741432448658806/reviews",
                            "language": "en",
                            "profile_photo_url": "https://lh3.googleusercontent.com/a-/ALV-UjViPOoR96GBR5-LsOGT4pXeVFpQMjVCD1ijGNNo5No04w=s128-c0x00000000-cc-rp-mo",
                            "rating": 5,
                            "relative_time_description": "4 months ago",
                            "text": "Very nice resort.. Room and beds are very clean.. And have a good playgrounds for kids. All staff are gentle and kind.. Most of all are very affordable.. I comeback again this coming december..",
                            "time": 1682165466
                            },
                            {
                            "author_name": "Sherwin Violango",
                            "author_url": "https://www.google.com/maps/contrib/114060647204175964533/reviews",
                            "language": "en",
                            "profile_photo_url": "https://lh3.googleusercontent.com/a-/ALV-UjV7AzTwntCShCufs4WsM8V00R23a4q5YaE-EAwF2F-rfA=s128-c0x00000000-cc-rp-mo",
                            "rating": 5,
                            "relative_time_description": "4 months ago",
                            "text": "If you want economic, budget friendly getaway without compromising quality accommodation with good facilities including a refreshing pool, best view of sunset, and a laid-back feel, this place is highly recommended.  Will definitely be back because of its atmosphere and environment...",
                            "time": 1682165084
                            },
                            {
                            "author_name": "Hasmin Paciente",
                            "author_url": "https://www.google.com/maps/contrib/111439243040011989945/reviews",
                            "language": "en",
                            "profile_photo_url": "https://lh3.googleusercontent.com/a/ACg8ocLuBlhBbWjzlpfIRrRZWD7luRvFBWEzbzC7T3WorcWL=s128-c0x00000000-cc-rp-mo",
                            "rating": 5,
                            "relative_time_description": "in the last week",
                            "text": "Nice and helpful staff, food is great! Highly satisfied.",
                            "time": 1694606203
                            },
                            {
                            "author_name": "Erlito Ortiz, Jr.",
                            "author_url": "https://www.google.com/maps/contrib/112426978896470032463/reviews",
                            "language": "en",
                            "profile_photo_url": "https://lh3.googleusercontent.com/a-/ALV-UjVP2DRBU__GhVia4Ai_Ep5IhRGNJla7ahkq8-ThNas294Y=s128-c0x00000000-cc-rp-mo",
                            "rating": 5,
                            "relative_time_description": "a week ago",
                            "text": "Clean and quick service on the staff, food was also great on my taste.",
                            "time": 1694079344
                            }
                        ],
                        "user_ratings_total": 97,
                        "website": "https://mariegoldvillaresort.com/",
                        "html_attributions": []
                    };
            }
        },
        mounted:function(){
            this.loadMapDetails();     
        },
        updated:function(){

        }
    }
</script>
