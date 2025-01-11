<?php
use iProtek\Core\Helpers\AppVarHelper;
?>
<strong>TERMS OF USE, SERVICE AND CONDITIONS</strong>
<div class="MsoNormal" style="line-height: 1.5;">
  <br />
</div>
<div class="MsoNormal" data-custom-class="subtitle" style="line-height: 1.5;">
  <strong>Last updated</strong>
  <strong>August 26, 2024</strong>
</div>
<div class="MsoNormal" style="line-height: 1.1;">
  <br />
</div>
<div class="MsoNormal" style="line-height: 115%;"><br /></div>
<div class="MsoNormal" style="line-height: 115%;"><br /></div>
<div style="line-height: 1.5;">
  <strong>
    <span data-custom-class="heading_1">AGREEMENT TO OUR LEGAL TERMS</span>
  </strong>
</div>
<div align="center" style="text-align: left;">
  <div class="MsoNormal" id="agreement" style="line-height: 1.5;">
    <a name="_6aa3gkhykvst"></a>
  </div>
</div>
<div align="center" style="line-height: 1;">
  <br />
</div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    >
    <?php
      $firstWord = function($string){
          // Trim leading and trailing spaces
          $trimmedString = trim($string);

          // Remove any special characters like comma
          $cleanedString = preg_replace('/[^a-zA-Z0-9\s]/', '', $trimmedString);

          // Split the string into an array of words
          $words = explode(" ", $cleanedString);

          // Get the first word (Hello)
          $firstWord = $words[0];

          return $firstWord;
      };

    ?>


      We are  {{ AppVarHelper::get('business_name',  config('app.name') ) }}, doing business as {{ $firstWord( AppVarHelper::get('business_name', config('app.name'))) }}
      ("<strong>Company</strong>," "<strong>we</strong>," "<strong>us</strong>,"
      "<strong>our</strong>")
      <span
        style="font-size:11.0pt;line-height:115%;Arial;mso-fareast-font-family:Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >
        <span
          style="font-size:11.0pt;line-height:115%; Arial;mso-fareast-font-family:Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
        >
          <span
            style="font-size:11.0pt;line-height:115%;Arial;mso-fareast-font-family:Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
          >
            <span
              style="font-size:11.0pt;line-height:115%; Arial;mso-fareast-font-family:Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
            ></span
            >, a company registered in the
             {{ AppVarHelper::get('business_address', 'Philippines at Purok Kamatis, Alegria , Cordova, Cebu 6017')  }}.
  </div>
</div>
<div align="center" style="line-height: 1;"><br /></div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >We operate the website
      <span style="color: rgb(0, 58, 250);"
        ><a
          href="<?=config('app.url')?>"
          target="_blank"
          data-custom-class="link"
          ><?=config('app.url')?></a
        ></span
      >
      (the "<strong>Site</strong>"), the mobile application <?=config('app.name') ?>
      (the "<strong>App</strong>"), as well as any other related products and
      services that refer or link to these legal terms (the "<strong
        >Legal Terms</strong
      >") (collectively, the "<strong>Services</strong>").</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    We provide and deliver from door to door.
  </div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    ></span>
  </div>
  <div class="MsoNormal" style="line-height: 1;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >You can contact us by phone at {{ AppVarHelper::get('business_mobile', '639081703461') }}, email at {{ AppVarHelper::get('business_email','info@iprotek.net') }}, or
      by mail to {{ AppVarHelper::get('business_address', 'Philippines at Purok Kamatis, Alegria , Cordova, Cebu 6017')  }}</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >These Legal Terms constitute a legally binding agreement made between
      you, whether personally or on behalf of an entity
      ("<strong>you</strong>"), and <?=config('app.name')?>, concerning your access
      to and use of the Services. You agree that by accessing the Services, you
      have read, understood, and agreed to be bound by all of these Legal Terms.
      IF YOU DO NOT AGREE WITH ALL OF THESE LEGAL TERMS, THEN YOU ARE EXPRESSLY
      PROHIBITED FROM USING THE SERVICES AND YOU MUST DISCONTINUE USE
      IMMEDIATELY.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >We will provide you with prior notice of any scheduled changes to the
      Services you are using. The modified Legal Terms will become effective
      upon posting or notifying you by admin@iprotek.net, as stated in
      the email message. By continuing to use the Services after the effective
      date of any changes, you agree to be bound by the modified terms.</span
    >
  </div>
</div>
<div align="center" style="line-height: 1;"><br /></div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;"
        >The Services are intended for users who are at least 13 years of age.
        All users who are minors in the jurisdiction in which they reside
        (generally under the age of 18) must have the permission of, and be
        directly supervised by, their parent or guardian to use the Services. If
        you are a minor, you must have your parent or guardian read and agree to
        these Legal Terms prior to you using the Services.</span
      ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;"></span
    ></span>
  </div>
  <div class="MsoNormal" style="line-height: 1;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    We recommend that you print a copy of these Legal Terms for your records.
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="heading_1"
    style="line-height: 1.5;"
  >
    <strong>TABLE OF CONTENTS</strong>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      href="#services"
      ><span data-custom-class="link"
        ><span style="color: rgb(0, 58, 250); font-size: 15px;"
          ><span data-custom-class="body_text">1. OUR SERVICES</span></span
        ></span
      ></a
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#ip"
      ><span style="color: rgb(0, 58, 250);"
        ><span data-custom-class="body_text"
          >2. INTELLECTUAL PROPERTY RIGHTS</span
        ></span
      ></a
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#userreps"
    ></a
    ><a
      data-custom-class="link"
      href="#userreps"
      ><span style="color: rbg(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >3. USER REPRESENTATIONS</span
        ></span
      ></a
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span style="font-size: 15px;"
      ><span data-custom-class="body_text"></span></span
    ><a
      data-custom-class="link"
      href="#userreg"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">4. USER REGISTRATION</span></span
      ></a
    ><span style="font-size: 15px;"
      ><span data-custom-class="body_text"></span
    ></span>
    <a
      data-custom-class="link"
      href="#products"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#products"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">5. PRODUCTS</span></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#purchases"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#purchases"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >6. PURCHASES AND PAYMENT</span
        ></span
      ></a
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span style="font-size: 15px;"></span>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span style="font-size: 15px;"
      ><span data-custom-class="body_text"></span></span
    ><a
      data-custom-class="link"
      href="#returnyes"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">7. RETURN POLICY</span></span
      ></a
    ><span style="font-size: 15px;"
      ><span data-custom-class="body_text"></span
    ></span>
    <a
      data-custom-class="link"
      href="#software"
    ></a>
    <a
      data-custom-class="link"
      href="#software"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#software"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"></span></span
    ></a>
    <a
      data-custom-class="link"
      href="#prohibited"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#prohibited"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >8. PROHIBITED ACTIVITIES</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#ugc"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#ugc"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >9. USER GENERATED CONTRIBUTIONS</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#license"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#license"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >10. CONTRIBUTION LICENSE</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#reviews"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#reviews"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >11. GUIDELINES FOR REVIEWS</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#mobile"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#mobile"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >12. MOBILE APPLICATION LICENSE</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#socialmedia"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#socialmedia"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"></span></span
    ></a>
    <a
      data-custom-class="link"
      href="#thirdparty"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#thirdparty"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"></span></span
    ></a>
    <a
      data-custom-class="link"
      href="#advertisers"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#advertisers"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"></span></span
    ></a>
    <a
      data-custom-class="link"
      href="#sitemanage"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#sitemanage"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >13. SERVICES MANAGEMENT</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#ppyes"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#ppyes"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">14. PRIVACY POLICY</span></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#ppno"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#ppno"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"></span></span
    ></a>
    <a
      data-custom-class="link"
      href="#dmca"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#dmca"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"></span></span
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span style="font-size: 15px;"
      ><span data-custom-class="body_text"></span
    ></span>
    <a
      data-custom-class="link"
      href="#terms"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#terms"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >15. TERM AND TERMINATION</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#modifications"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#modifications"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >16. MODIFICATIONS AND INTERRUPTIONS</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#law"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#law"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">17. GOVERNING LAW</span></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#disputes"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#disputes"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">18. DISPUTE RESOLUTION</span></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#corrections"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#corrections"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">19. CORRECTIONS</span></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#disclaimer"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#disclaimer"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">20. DISCLAIMER</span></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#liability"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#liability"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >21. LIMITATIONS OF LIABILITY</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#indemnification"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#indemnification"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">22. INDEMNIFICATION</span></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#userdata"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#userdata"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">23. USER DATA</span></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#electronic"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#electronic"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"
          >24. ELECTRONIC COMMUNICATIONS, TRANSACTIONS, AND SIGNATURES</span
        ></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#california"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span style="font-size: 15px;"></span>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span style="color: rgb(0, 58, 250); font-size: 15px;"
      ><a
        data-custom-class="link"
        href="#sms"
        ><span data-custom-class="body_text">25. SMS TEXT MESSAGING</span></a
      ></span
    ><span style="font-size: 15px;"></span>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#california"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text"></span></span
    ></a>
    <a
      data-custom-class="link"
      href="#misc"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#misc"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">26. MISCELLANEOUS</span></span
      ></a
    >
    <a
      data-custom-class="link"
      href="#contact"
    ></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <a
      data-custom-class="link"
      href="#contact"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        ><span data-custom-class="body_text">27. CONTACT US</span></span
      ></a
    >
  </div>
</div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="heading_1"
    style="line-height: 1.5;"
  >
    <a name="_b6y29mp52qvx"></a>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="heading_1"
    id="services"
    style="line-height: 1.5;"
  >
    <strong><span style="font-size: 19px;">1. OUR SERVICES</span></strong>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span style="font-size: 15px;"
      >The information provided when using the Services is not intended for
      distribution to or use by any person or entity in any jurisdiction or
      country where such distribution or use would be contrary to law or
      regulation or which would subject us to any registration requirement
      within such jurisdiction or country. Accordingly, those persons who choose
      to access the Services from other locations do so on their own initiative
      and are solely responsible for compliance with local laws, if and to the
      extent local laws are applicable.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
</div>
<div
  align="center"
  data-custom-class="heading_1"
  style="text-align: left; line-height: 1.5;"
>
  <strong
    ><span id="ip" style="font-size: 19px;"
      >2. INTELLECTUAL PROPERTY RIGHTS</span
    ></strong
  >
</div>
<div align="center" style="line-height: 1.5;"><br /></div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="heading_2"
    style="line-height: 1.5;"
  >
    <strong>Our intellectual property</strong>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >We are the owner or the licensee of all intellectual property rights in
      our Services, including all source code, databases, functionality,
      software, website designs, audio, video, text, photographs, and graphics
      in the Services (collectively, the "Content"), as well as the trademarks,
      service marks, and logos contained therein (the "Marks").</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >Our Content and Marks are protected by copyright and trademark laws (and
      various other intellectual property rights and unfair competition laws)
      and treaties in the United States and around the world.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >The Content and Marks are provided in or through the Services "AS IS" for
      your personal, non-commercial use or internal business purpose only.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="heading_2"
    style="line-height: 1.5;"
  >
    <strong>Your use of our Services</strong>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span style="font-size: 15px;"
      >Subject to your compliance with these Legal Terms, including the "</span
    ><a
      data-custom-class="link"
      href="#prohibited"
      ><span style="color: rgb(0, 58, 250); font-size: 15px;"
        >PROHIBITED ACTIVITIES</span
      ></a
    ><span style="font-size: 15px;"
      >" section below, we grant you a non-exclusive, non-transferable,
      revocable license to:</span
    >
  </div>
  <ul>
    <li
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span style="font-size: 15px;">access the Services; and</span>
    </li>
    <li
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span style="font-size: 15px;"
        >download or print a copy of any portion of the Content to which you
        have properly gained access.</span
      >
    </li>
  </ul>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >solely for your personal, non-commercial use or internal business
      purpose.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >Except as set out in this section or elsewhere in our Legal Terms, no
      part of the Services and no Content or Marks may be copied, reproduced,
      aggregated, republished, uploaded, posted, publicly displayed, encoded,
      translated, transmitted, distributed, sold, licensed, or otherwise
      exploited for any commercial purpose whatsoever, without our express prior
      written permission.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >If you wish to make any use of the Services, Content, or Marks other than
      as set out in this section or elsewhere in our Legal Terms, please address
      your request to: info@iprotek.com. If we ever grant you the permission to
      post, reproduce, or publicly display any part of our Services or Content,
      you must identify us as the owners or licensors of the Services, Content,
      or Marks and ensure that any copyright or proprietary notice appears or is
      visible on posting, reproducing, or displaying our Content.</span
    >
  </div>
</div>
<div align="center" style="line-height: 1.5;"><br /></div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >We reserve all rights not expressly granted to you in and to the
      Services, Content, and Marks.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >Any breach of these Intellectual Property Rights will constitute a
      material breach of our Legal Terms and your right to use our Services will
      terminate immediately.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="heading_2"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      ><strong>Your submissions and contributions</strong><strong></strong
    ></span>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span style="font-size: 15px;"
      >Please review this section and the "<a
        data-custom-class="link"
        href="#prohibited"
        ><span style="color: rgb(0, 58, 250);">PROHIBITED ACTIVITIES</span></a
      >" section carefully prior to using our Services to understand the (a)
      rights you give us and (b) obligations you have when you post or upload
      any content through the Services.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span style="font-size: 15px;"
      ><strong>Submissions:</strong> By directly sending us any question,
      comment, suggestion, idea, feedback, or other information about the
      Services ("Submissions"), you agree to assign to us all intellectual
      property rights in such Submission. You agree that we shall own this
      Submission and be entitled to its unrestricted use and dissemination for
      any lawful purpose, commercial or otherwise, without acknowledgment or
      compensation to you.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span style="font-size: 15px;"
      ><strong>Contributions:</strong> The Services may invite you to chat,
      contribute to, or participate in blogs, message boards, online forums, and
      other functionality during which you may create, submit, post, display,
      transmit, publish, distribute, or broadcast content and materials to us or
      through the Services, including but not limited to text, writings, video,
      audio, photographs, music, graphics, comments, reviews, rating
      suggestions, personal information, or other material ("Contributions").
      Any Submission that is publicly posted shall also be treated as a
      Contribution.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span style="font-size: 15px;"
      >You understand that Contributions may be viewable by other users of the
      Services.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span style="font-size: 15px;"
      ><strong
        >When you post Contributions, you grant us a license (including use of
        your name, trademarks, and logos):&nbsp;</strong
      >By posting any Contributions, you grant us an unrestricted, unlimited,
      irrevocable, perpetual, non-exclusive, transferable, royalty-free,
      fully-paid, worldwide right, and license to: use, copy, reproduce,
      distribute, sell, resell, publish, broadcast, retitle, store, publicly
      perform, publicly display, reformat, translate, excerpt (in whole or in
      part), and exploit your Contributions (including, without limitation, your
      image, name, and voice) for any purpose, commercial, advertising, or
      otherwise, to prepare derivative works of, or incorporate into other
      works, your Contributions, and to sublicense the licenses granted in this
      section. Our use and distribution may occur in any media formats and
      through any media channels.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span style="font-size: 15px;"
      >This license includes our use of your name, company name, and franchise
      name, as applicable, and any of the trademarks, service marks, trade
      names, logos, and personal and commercial images you provide.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span style="font-size: 15px;"
      ><strong>You are responsible for what you post or upload:</strong> By
      sending us Submissions and/or posting Contributions through any part of
      the Services or making Contributions accessible through the Services by
      linking your account through the Services to any of your social networking
      accounts, you:</span
    >
  </div>
  <ul>
    <li
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span style="font-size: 15px;"
        >confirm that you have read and agree with our "</span
      ><a
        data-custom-class="link"
        href="#prohibited"
        ><span style="color: rgb(0, 58, 250); font-size: 15px;"
          >PROHIBITED ACTIVITIES</span
        ></a
      ><span style="font-size: 15px;"
        >" and will not post, send, publish, upload, or transmit through the
        Services any Submission nor post any Contribution that is illegal,
        harassing, hateful, harmful, defamatory, obscene, bullying, abusive,
        discriminatory, threatening to any person or group, sexually explicit,
        false, inaccurate, deceitful, or misleading;</span
      >
    </li>
    <li
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span style="font-size: 15px;"
        >to the extent permissible by applicable law, waive any and all moral
        rights to any such Submission and/or Contribution;</span
      >
    </li>
    <li
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span style="font-size: 15px;"
        >warrant that any such Submission and/or Contributions are original to
        you or that you have the necessary rights and licenses to submit such
        Submissions and/or Contributions and that you have full authority to
        grant us the above-mentioned rights in relation to your Submissions
        and/or Contributions; and</span
      >
    </li>
    <li
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span style="font-size: 15px;"
        >warrant and represent that your Submissions and/or Contributions do not
        constitute confidential information.</span
      >
    </li>
  </ul>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    You are solely responsible for your Submissions and/or Contributions and you
    expressly agree to reimburse us for any and all losses that we may suffer
    because of your breach of (a) this section, (b) any third party’s
    intellectual property rights, or (c) applicable law.
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <strong>We may remove or edit your Content:</strong> Although we have no
    obligation to monitor any Contributions, we shall have the right to remove
    or edit any Contributions at any time without notice if in our reasonable
    opinion we consider such Contributions harmful or in breach of these Legal
    Terms. If we remove or edit any such Contributions, we may also suspend or
    disable your account and report you to the authorities.
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
</div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="heading_1"
    id="userreps"
    style="line-height: 1.5;"
  >
    <a name="_5hg7kgyv9l8z"></a
    ><strong
      ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
        ><strong
          ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
            ><strong
              ><span
                style="line-height: 115%; font-family: Arial; font-size: 19px;"
                ><strong
                  ><span
                    style="line-height: 115%; font-family: Arial; font-size: 19px;"
                    >3.</span
                  ></strong
                ></span
              >&nbsp;</strong
            ></span
          ></strong
        >USER REPRESENTATIONS</span
      ></strong
    >
  </div>
</div>
<div align="center" style="line-height: 1.5;"><br /></div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >By using the Services, you represent and warrant that:</span
    >
    <span style="color: rgb(89, 89, 89); font-size: 11pt;">(</span
    ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;">1</span
    ><span style="color: rgb(89, 89, 89); font-size: 11pt;"
      >) all registration information you submit will be true, accurate,
      current, and complete; (</span
    ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;">2</span
    ><span style="color: rgb(89, 89, 89); font-size: 11pt;"
      >) you will maintain the accuracy of such information and promptly update
      such registration information as necessary;</span
    >&nbsp;<span style="color: rgb(89, 89, 89); font-size: 11pt;">(</span
    ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;">3</span
    ><span style="color: rgb(89, 89, 89); font-size: 11pt;"
      >) you have the legal capacity and you agree to comply with these Legal
      Terms;</span
    >
    <span style="color: rgb(89, 89, 89); font-size: 11pt;">(</span
    ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;">4</span
    ><span style="color: rgb(89, 89, 89); font-size: 11pt;"
      >) you are not under the age of 13;</span
    >&nbsp;<span style="color: rgb(89, 89, 89); font-size: 11pt;">(</span
    ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;">5</span
    ><span style="color: rgb(89, 89, 89); font-size: 11pt;"
      >) you are not a minor in the jurisdiction in which you reside, or if a
      minor, you have received parental permission to use the Services; (</span
    ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;">6</span
    ><span style="color: rgb(89, 89, 89); font-size: 11pt;"
      >) you will not access the Services through automated or non-human means,
      whether through a bot, script or otherwise; (</span
    ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;">7</span
    ><span style="color: rgb(89, 89, 89); font-size: 11pt;"
      >) you will not use the Services for any illegal or unauthorized purpose;
      and (</span
    ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;">8</span
    ><span style="color: rgb(89, 89, 89); font-size: 11pt;"
      >) your use of the Services will not violate any applicable law or
      regulation.</span
    ><span style="color: rgb(89, 89, 89); font-size: 14.6667px;"></span>
  </div>
</div>
<div align="center" style="line-height: 1.5;"><br /></div>
<div align="center" style="text-align: left;">
  <div class="MsoNormal" style="text-align: justify; line-height: 115%;">
    <div class="MsoNormal" style="line-height: 17.25px;">
      <div
        class="MsoNormal"
        data-custom-class="body_text"
        style="line-height: 1.5; text-align: left;"
      >
        <span
          style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
          >If you provide any information that is untrue, inaccurate, not
          current, or incomplete, we have the right to suspend or terminate your
          account and refuse any and all current or future use of the Services
          (or any portion thereof).</span
        >
      </div>
      <div class="MsoNormal" style="line-height: 1.1; text-align: left;"></div>
      <div class="MsoNormal" style="line-height: 1.5; text-align: left;">
        <br />
      </div>
    </div>
    <div class="MsoNormal" style="line-height: 1;">
      <div
        class="MsoNormal"
        data-custom-class="heading_1"
        id="userreg"
        style="line-height: 1.5; text-align: left;"
      >
        <strong
          ><span style="line-height: 24.5333px; font-size: 19px;"
            ><strong
              ><span
                style="line-height: 115%; font-family: Arial; font-size: 19px;"
                ><strong
                  ><span
                    style="line-height: 115%; font-family: Arial; font-size: 19px;"
                    ><strong
                      ><span
                        style="line-height: 115%; font-family: Arial; font-size: 19px;"
                        >4.</span
                      ></strong
                    ></span
                  >&nbsp;</strong
                ></span
              ></strong
            >USER REGISTRATION</span
          ></strong
        >
      </div>
    </div>
    <div class="MsoNormal" style="line-height: 1.5; text-align: left;">
      <br />
    </div>
    <div class="MsoNormal" style="line-height: 1;">
      <div
        class="MsoNormal"
        data-custom-class="body_text"
        style="text-align: left; line-height: 1.5;"
      >
        <span
          style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
          >You may be required to register to use the Services. You agree to
          keep your password confidential and will be responsible for all use of
          your account and password. We reserve the right to remove, reclaim, or
          change a username you select if we determine, in our sole discretion,
          that such username is inappropriate, obscene, or otherwise
          objectionable.</span
        >
      </div>
    </div>
    <div class="MsoNormal" style="line-height: 1.5; text-align: left;">
      <span style="font-size: 15px;"></span
      ><span style="font-size: 15px;"></span>
    </div>
    <div class="MsoNormal" style="line-height: 1.5; text-align: left;">
      <br />
    </div>
    <div
      class="MsoNormal"
      data-custom-class="heading_1"
      id="products"
      style="line-height: 1.5; text-align: left;"
    >
      <span style="font-size: 19px;"><strong>5. PRODUCTS</strong></span>
    </div>
    <div class="MsoNormal" style="line-height: 1.5; text-align: left;">
      <br />
    </div>
    <div
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5; text-align: left;"
    >
      <span style="font-size: 15px;"></span
      ><span style="font-size: 15px;"
        >We make every effort to display as accurately as possible the colors,
        features, specifications, and details of the products available on the
        Services. However, we do not guarantee that the colors, features,
        specifications, and details of the products will be accurate, complete,
        reliable, current, or free of other errors, and your electronic display
        may not accurately reflect the actual colors and details of the
        products. All products are subject to availability, and we cannot
        guarantee that items will be in stock. We reserve the right to
        discontinue any products at any time for any reason. Prices for all
        products are subject to change.</span
      >
    </div>
    <div class="MsoNormal" style="line-height: 1.5; text-align: left;">
      <span style="font-size: 15px;"></span>
    </div>
    <div class="MsoNormal" style="line-height: 1.5; text-align: left;">
      <br />
    </div>
  </div>
</div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="heading_1"
    id="purchases"
    style="line-height: 1.5;"
  >
    <a name="_ynub0jdx8pob"></a
    ><strong
      ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
        ><strong
          ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
            ><strong
              ><span
                style="line-height: 115%; font-family: Arial; font-size: 19px;"
                ><strong
                  ><span
                    style="line-height: 115%; font-family: Arial; font-size: 19px;"
                    >6.</span
                  ></strong
                ></span
              ></strong
            ></span
          >&nbsp;</strong
        >PURCHASES AND PAYMENT</span
      ></strong
    >
  </div>
</div>
<div align="center" style="line-height: 1.5;"><br /></div>
<div align="center" style="text-align: left;">
  <div class="MsoNormal" style="line-height: 1.5;">
    <span style="font-size: 15px;"></span>
  </div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >We accept the following forms of payment:</span
    >
  </div>
  <div class="MsoNormal" style="text-align:justify;line-height:115%;">
    <div class="MsoNormal" style="text-align: left; line-height: 1;">
      <br />
    </div>
  </div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5; margin-left: 20px;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >- &nbsp;GCASH</span
    >
  </div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5; margin-left: 20px;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >- &nbsp;BANK TRANSFER</span
    >
  </div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5; margin-left: 20px;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >- &nbsp;PayPal</span
    >
  </div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5; margin-left: 20px;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    ></span>
  </div>
  <div class="MsoNormal" style="line-height: 1;">
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      ><br
    /></span>
  </div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >You agree to provide current, complete, and accurate purchase and account
      information for all purchases made via the Services. You further agree to
      promptly update account and payment information, including email address,
      payment method, and payment card expiration date, so that we can complete
      your transactions and contact you as needed. Sales tax will be added to
      the price of purchases as deemed required by us. We may change prices at
      any time. All payments shall be&nbsp;</span
    ><span
      style="font-size: 15px; line-height: 115%; font-family: Arial; color: rgb(89, 89, 89);"
      >in PHP.</span
    >
  </div>
</div>
<div align="center" style="line-height: 1.5;"><br /></div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >You agree to pay all charges at the prices then in effect for your
      purchases and any applicable shipping fees, and you authorize us to charge
      your chosen payment provider for any such amounts upon placing your order.
      We reserve the right to correct any errors or mistakes in pricing, even if
      we have already requested or received payment.</span
    >
  </div>
</div>
<div align="center" style="line-height: 1.5;"><br /></div>
<div align="center" style="text-align: left;">
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      >We reserve the right to refuse any order placed through the Services. We
      may, in our sole discretion, limit or cancel quantities purchased per
      person, per household, or per order. These restrictions may include orders
      placed by or under the same customer account, the same payment method,
      and/or orders that use the same billing or shipping address. We reserve
      the right to limit or prohibit orders that, in our sole judgment, appear
      to be placed by dealers, resellers, or distributors.</span
    ><span
      style="line-height: 115%; font-family: Arial; color: rgb(89, 89, 89);"
    ></span>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span style="font-size: 15px;"></span><span style="font-size: 15px;"></span
    ><span style="font-size: 15px;"></span>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="heading_1"
    id="returnyes"
    style="line-height: 1.5;"
  >
    <span style="font-size: 19px;"><strong>7. RETURN POLICY</strong></span>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    Please review our Return Policy posted on the Services prior to making any
    purchases.
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div class="MsoNormal" style="text-align: justify; line-height: 1.5;">
    <span style="line-height: 115%; font-family: Arial; color: rgb(89, 89, 89);"
      ><div class="MsoNormal" style="font-size: 15px; line-height: 1.5;">
        <br /></div
    ></span>
    <div
      class="MsoNormal"
      data-custom-class="heading_1"
      id="prohibited"
      style="text-align: left; line-height: 1.5;"
    >
      <strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  ><strong
                    ><span
                      style="line-height: 115%; font-family: Arial; font-size: 19px;"
                      >8.</span
                    ></strong
                  ></span
                ></strong
              ></span
            >&nbsp;</strong
          >PROHIBITED ACTIVITIES</span
        ></strong
      >
    </div>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="text-align: justify; line-height: 1;">
    <div
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5; text-align: left;"
    >
      <span
        style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        >You may not access or use the Services for any purpose other than that
        for which we make the Services available. The Services may not be used
        in connection with any commercial endeavors except those that are
        specifically endorsed or approved by us.</span
      >
    </div>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="text-align: justify; line-height: 1;">
    <div class="MsoNormal" style="line-height: 17.25px;">
      <div class="MsoNormal" style="line-height: 1.1;">
        <div class="MsoNormal" style="line-height: 17.25px;">
          <div
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span
              style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
              >As a user of the Services, you agree not to:</span
            >
          </div>
        </div>
        <ul>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span
              style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
              ><span
                style="font-family: sans-serif; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                >Systematically retrieve data or other content from the Services
                to create or compile, directly or indirectly, a collection,
                compilation, database, or directory without written permission
                from us.</span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Trick, defraud, or mislead us and other users, especially
                      in any attempt to learn sensitive account information such
                      as user passwords.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Circumvent, disable, or otherwise interfere with
                      security-related features of the Services, including
                      features that prevent or restrict the use or copying of
                      any Content or enforce limitations on the use of the
                      Services and/or the Content contained therein.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Disparage, tarnish, or otherwise harm, in our opinion, us
                      and/or the Services.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Use any information obtained from the Services in order
                      to harass, abuse, or harm another person.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Make improper use of our support services or submit false
                      reports of abuse or misconduct.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Use the Services in a manner inconsistent with any
                      applicable laws or regulations.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Engage in unauthorized framing of or linking to the
                      Services.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Upload or transmit (or attempt to upload or to transmit)
                      viruses, Trojan horses, or other material, including
                      excessive use of capital letters and spamming (continuous
                      posting of repetitive text), that interferes with any
                      party’s uninterrupted use and enjoyment of the Services or
                      modifies, impairs, disrupts, alters, or interferes with
                      the use, features, functions, operation, or maintenance of
                      the Services.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Engage in any automated use of the system, such as using
                      scripts to send comments or messages, or using any data
                      mining, robots, or similar data gathering and extraction
                      tools.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Delete the copyright or other proprietary rights notice
                      from any Content.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Attempt to impersonate another user or person or use the
                      username of another user.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Upload or transmit (or attempt to upload or to transmit)
                      any material that acts as a passive or active information
                      collection or transmission mechanism, including without
                      limitation, clear graphics interchange formats ("gifs"),
                      1×1 pixels, web bugs, cookies, or other similar devices
                      (sometimes referred to as "spyware" or "passive collection
                      mechanisms" or "pcms").</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Interfere with, disrupt, or create an undue burden on the
                      Services or the networks or services connected to the
                      Services.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Harass, annoy, intimidate, or threaten any of our
                      employees or agents engaged in providing any portion of
                      the Services to you.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Attempt to bypass any measures of the Services designed
                      to prevent or restrict access to the Services, or any
                      portion of the Services.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Copy or adapt the Services' software, including but not
                      limited to Flash, PHP, HTML, JavaScript, or other
                      code.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Except as permitted by applicable law, decipher,
                      decompile, disassemble, or reverse engineer any of the
                      software comprising or in any way making up a part of the
                      Services.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Except as may be the result of standard search engine or
                      Internet browser usage, use, launch, develop, or
                      distribute any automated system, including without
                      limitation, any spider, robot, cheat utility, scraper, or
                      offline reader that accesses the Services, or use or
                      launch any unauthorized script or other software.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Use a buying agent or purchasing agent to make purchases
                      on the Services.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Make any unauthorized use of the Services, including
                      collecting usernames and/or email addresses of users by
                      electronic or other means for the purpose of sending
                      unsolicited email, or creating user accounts by automated
                      means or under false pretenses.</span
                    ></span
                  ></span
                ></span
              ></span
            >
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span style="font-size: 15px;"
              ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                ><span
                  style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                  ><span style="line-height: 16.8667px; color: rgb(89, 89, 89);"
                    ><span
                      style="font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                      >Use the Services as part of any effort to compete with us
                      or otherwise use the Services and/or the Content for any
                      revenue-generating endeavor or commercial
                      enterprise.</span
                    ><span
                      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);font-family: sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: justify; text-indent: -29.4px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; color: rgb(89, 89, 89);"
                    ></span></span></span></span
            ></span>
          </li>
          <li
            class="MsoNormal"
            data-custom-class="body_text"
            style="line-height: 1.5; text-align: left;"
          >
            <span
              style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
              >Buy Goods and Track Purchase Records</span
            >
          </li>
        </ul>
      </div>
      <div class="MsoNormal" style="line-height: 1.5; text-align: left;">
        <br />
      </div>
    </div>
    <div class="MsoNormal" style="line-height: 17.25px;">
      <div class="MsoNormal" style="line-height: 1;">
        <div
          class="MsoNormal"
          data-custom-class="heading_1"
          id="ugc"
          style="line-height: 1.5;"
        >
          <strong
            ><span style="line-height: 24.5333px; font-size: 19px;"
              ><strong
                ><span style="line-height: 24.5333px; font-size: 19px;"
                  ><strong
                    ><span
                      style="line-height: 115%; font-family: Arial; font-size: 19px;"
                      ><strong
                        ><span
                          style="line-height: 115%; font-family: Arial; font-size: 19px;"
                          >9.</span
                        ></strong
                      ></span
                    ></strong
                  ></span
                >&nbsp;</strong
              >USER GENERATED CONTRIBUTIONS</span
            ></strong
          >
        </div>
      </div>
      <div class="MsoNormal" style="line-height: 1.5; text-align: left;">
        <br />
      </div>
      <div class="MsoNormal" style="line-height: 1;">
        <div
          class="MsoNormal"
          data-custom-class="body_text"
          style="line-height: 1.5;"
        >
          <span
            style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
            >The Services may invite you to chat, contribute to, or participate
            in blogs, message boards, online forums, and other functionality,
            and may provide you with the opportunity to create, submit, post,
            display, transmit, perform, publish, distribute, or broadcast
            content and materials to us or on the Services, including but not
            limited to text, writings, video, audio, photographs, graphics,
            comments, suggestions, or personal information or other material
            (collectively, "Contributions"). Contributions may be viewable by
            other users of the Services and through third-party websites. As
            such, any Contributions you transmit may be treated as
            non-confidential and non-proprietary. When you create or make
            available any Contributions, you thereby represent and warrant
            that:</span
          >
        </div>
        &nbsp;&nbsp;&nbsp;
      </div>
      <ul style="font-size: medium;text-align: left;">
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >The creation, distribution, transmission, public display, or
                performance, and the accessing, downloading, or copying of your
                Contributions do not and will not infringe the proprietary
                rights, including but not limited to the copyright, patent,
                trademark, trade secret, or moral rights of any third
                party.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >You are the creator and owner of or have the necessary
                licenses, rights, consents, releases, and permissions to use and
                to authorize us, the Services, and other users of the Services
                to use your Contributions in any manner contemplated by the
                Services and these Legal Terms.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >You have the written consent, release, and/or permission of
                each and every identifiable individual person in your
                Contributions to use the name or likeness of each and every such
                identifiable individual person to enable inclusion and use of
                your Contributions in any manner contemplated by the Services
                and these Legal Terms.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions are not false, inaccurate, or
                misleading.</span
              ></span
            >&nbsp;</span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions are not unsolicited or unauthorized
                advertising, promotional materials, pyramid schemes, chain
                letters, spam, mass mailings, or other forms of
                solicitation.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions are not obscene, lewd, lascivious, filthy,
                violent, harassing, libelous, slanderous, or otherwise
                objectionable (as determined by us).</span
              ></span
            >&nbsp;</span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions do not ridicule, mock, disparage,
                intimidate, or abuse anyone.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions are not used to harass or threaten (in the
                legal sense of those terms) any other person and to promote
                violence against a specific person or class of people.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions do not violate any applicable law,
                regulation, or rule.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions do not violate the privacy or publicity
                rights of any third party.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions do not violate any applicable law concerning
                child pornography, or otherwise intended to protect the health
                or well-being of minors.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions do not include any offensive comments that
                are connected to race, national origin, gender, sexual
                preference, or physical handicap.</span
              ></span
            ></span
          >
        </li>
        <li data-custom-class="body_text" style="line-height: 1.5;">
          <span style="color: rgb(89, 89, 89);"
            ><span style="font-size: 14px;"
              ><span data-custom-class="body_text"
                >Your Contributions do not otherwise violate, or link to
                material that violates, any provision of these Legal Terms, or
                any applicable law or regulation.</span
              ></span
            ></span
          >
        </li>
      </ul>
    </div>
  </div>
  <div class="MsoNormal" style="text-align: justify; line-height: 1.5;">
    <div
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span
        style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        >Any use of the Services in violation of the foregoing violates these
        Legal Terms and may result in, among other things, termination or
        suspension of your rights to use the Services.</span
      >
    </div>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="text-align: justify; line-height: 1;">
    <div
      class="MsoNormal"
      data-custom-class="heading_1"
      id="license"
      style="line-height: 1.5;"
    >
      <strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span style="line-height: 24.5333px; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  ><strong
                    ><span
                      style="line-height: 115%; font-family: Arial; font-size: 19px;"
                      >10.</span
                    ></strong
                  ></span
                ></strong
              ></span
            >&nbsp;</strong
          >CONTRIBUTION LICENSE</span
        ></strong
      >
    </div>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="line-height: 1;">
    <div
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span
        style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        >By posting your Contributions to any part of the Services, you
        automatically grant, and you represent and warrant that you have the
        right to grant, to us an unrestricted, unlimited, irrevocable,
        perpetual, non-exclusive, transferable, royalty-free, fully-paid,
        worldwide right, and license to host, use, copy, reproduce, disclose,
        sell, resell, publish, broadcast, retitle, archive, store, cache,
        publicly perform, publicly display, reformat, translate, transmit,
        excerpt (in whole or in part), and distribute such Contributions
        (including, without limitation, your image and voice) for any purpose,
        commercial, advertising, or otherwise, and to prepare derivative works
        of, or incorporate into other works, such Contributions, and grant and
        authorize sublicenses of the foregoing. The use and distribution may
        occur in any media formats and through any media channels.</span
      >
    </div>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="text-align: justify; line-height: 1;">
    <div
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span
        style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        >This license will apply to any form, media, or technology now known or
        hereafter developed, and includes our use of your name, company name,
        and franchise name, as applicable, and any of the trademarks, service
        marks, trade names, logos, and personal and commercial images you
        provide. You waive all moral rights in your Contributions, and you
        warrant that moral rights have not otherwise been asserted in your
        Contributions.</span
      >
    </div>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="text-align: justify; line-height: 1;">
    <div
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span
        style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        >We do not assert any ownership over your Contributions. You retain full
        ownership of all of your Contributions and any intellectual property
        rights or other proprietary rights associated with your Contributions.
        We are not liable for any statements or representations in your
        Contributions provided by you in any area on the Services. You are
        solely responsible for your Contributions to the Services and you
        expressly agree to exonerate us from any and all responsibility and to
        refrain from any legal action against us regarding your
        Contributions.</span
      >
    </div>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="text-align: justify; line-height: 1;">
    <div
      class="MsoNormal"
      data-custom-class="body_text"
      style="line-height: 1.5;"
    >
      <span
        style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        >We have the right, in our sole and absolute discretion, (1) to edit,
        redact, or otherwise change any Contributions; (2) to re-categorize any
        Contributions to place them in more appropriate locations on the
        Services; and (3) to pre-screen or delete any Contributions at any time
        and for any reason, without notice. We have no obligation to monitor
        your Contributions.</span
      >
    </div>
  </div>
</div>
<div align="center" style="text-align: left;">
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
    ></span>
  </div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  ></div>
  <div
    class="MsoNormal"
    data-custom-class="heading_1"
    id="reviews"
    style="line-height: 1.5;"
  >
    <strong
      ><span style="line-height: 24.5333px; font-size: 19px;"
        ><strong
          ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
            ><strong
              ><span
                style="line-height: 115%; font-family: Arial; font-size: 19px;"
                >11.</span
              ></strong
            ></span
          ></strong
        >&nbsp;</span
      >GUIDELINES FOR REVIEWS</strong
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      >We may provide you areas on the Services to leave reviews or ratings.
      When posting a review, you must comply with the following criteria: (1)
      you should have firsthand experience with the person/entity being
      reviewed; (2) your reviews should not contain offensive profanity, or
      abusive, racist, offensive, or hateful language; (3) your reviews should
      not contain discriminatory references based on religion, race, gender,
      national origin, age, marital status, sexual orientation, or disability;
      (4) your reviews should not contain references to illegal activity; (5)
      you should not be affiliated with competitors if posting negative reviews;
      (6) you should not make any conclusions as to the legality of conduct; (7)
      you may not post any false or misleading statements; and (8) you may not
      organize a campaign encouraging others to post reviews, whether positive
      or negative.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      ><span
        style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        >We may accept, reject, or remove reviews in our sole discretion. We
        have absolutely no obligation to screen reviews or to delete reviews,
        even if anyone considers reviews objectionable or inaccurate. Reviews
        are not endorsed by us, and do not necessarily represent our opinions or
        the views of any of our affiliates or partners. We do not assume
        liability for any review or for any claims, liabilities, or losses
        resulting from any review. By posting a review, you hereby grant to us a
        perpetual, non-exclusive, worldwide, royalty-free, fully paid,
        assignable, and sublicensable right and license to reproduce, modify,
        translate, transmit by any means, display, perform, and/or distribute
        all content relating to review.</span
      ></span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      ><span
        style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        ><span
          style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        ></span></span
    ></span>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
  <div
    class="MsoNormal"
    data-custom-class="heading_1"
    id="mobile"
    style="line-height: 1.5;"
  >
    <strong
      ><span style="line-height: 24.5333px; font-size: 19px;"
        ><strong
          ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
            ><strong
              ><span
                style="line-height: 115%; font-family: Arial; font-size: 19px;"
                >12.</span
              ></strong
            ></span
          ></strong
        >&nbsp;</span
      >MOBILE APPLICATION LICENSE</strong
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="heading_2"
    style="line-height: 1.5;"
  >
    <strong>Use License</strong>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      >If you access the Services via the App, then we grant you a revocable,
      non-exclusive, non-transferable, limited right to install and use the App
      on wireless electronic devices owned or controlled by you, and to access
      and use the App on such devices strictly in accordance with the terms and
      conditions of this mobile application license contained in these Legal
      Terms. You shall not: (1) except as permitted by applicable law,
      decompile, reverse engineer, disassemble, attempt to derive the source
      code of, or decrypt the App; (2) make any modification, adaptation,
      improvement, enhancement, translation, or derivative work from the App;
      (3) violate any applicable laws, rules, or regulations in connection with
      your access or use of the App; (4) remove, alter, or obscure any
      proprietary notice (including any notice of copyright or trademark) posted
      by us or the licensors of the App; (5) use the App for any
      revenue-generating endeavor, commercial enterprise, or other purpose for
      which it is not designed or intended; (6) make the App available over a
      network or other environment permitting access or use by multiple devices
      or users at the same time; (7) use the App for creating a product,
      service, or software that is, directly or indirectly, competitive with or
      in any way a substitute for the App; (8) use the App to send automated
      queries to any website or to send any unsolicited commercial email; or (9)
      use any proprietary information or any of our interfaces or our other
      intellectual property in the design, development, manufacture, licensing,
      or distribution of any applications, accessories, or devices for use with
      the App.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="heading_2"
    style="line-height: 1.5;"
  >
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      ><strong>Apple and Android Devices</strong></span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div
    class="MsoNormal"
    data-custom-class="body_text"
    style="line-height: 1.5;"
  >
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      >The following terms apply when you use the App obtained from either the
      Apple Store or Google Play (each an "App Distributor") to access the
      Services: (1) the license granted to you for our App is limited to a
      non-transferable license to use the application on a device that utilizes
      the Apple iOS or Android operating systems, as applicable, and in
      accordance with the usage rules set forth in the applicable App
      Distributor’s terms of service; (2) we are responsible for providing any
      maintenance and support services with respect to the App as specified in
      the terms and conditions of this mobile application license contained in
      these Legal Terms or as otherwise required under applicable law, and you
      acknowledge that each App Distributor has no obligation whatsoever to
      furnish any maintenance and support services with respect to the App; (3)
      in the event of any failure of the App to conform to any applicable
      warranty, you may notify the applicable App Distributor, and the App
      Distributor, in accordance with its terms and policies, may refund the
      purchase price, if any, paid for the App, and to the maximum extent
      permitted by applicable law, the App Distributor will have no other
      warranty obligation whatsoever with respect to the App; (4) you represent
      and warrant that (i) you are not located in a country that is subject to a
      US government embargo, or that has been designated by the US government as
      a "terrorist supporting" country and (ii) you are not listed on any US
      government list of prohibited or restricted parties; (5) you must comply
      with applicable third-party terms of agreement when using the App, e.g.,
      if you have a VoIP application, then you must not be in violation of their
      wireless data service agreement when using the App; and (6) you
      acknowledge and agree that the App Distributors are third-party
      beneficiaries of the terms and conditions in this mobile application
      license contained in these Legal Terms, and that each App Distributor will
      have the right (and will be deemed to have accepted the right) to enforce
      the terms and conditions in this mobile application license contained in
      these Legal Terms against you as a third-party beneficiary thereof.</span
    >
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"><br /></div>
  <div class="MsoNormal" style="line-height: 1.5;">
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      ><span
        style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        ><span
          style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
        ></span></span
    ></span>
  </div>
  <div class="MsoNormal" style="line-height: 1.5;"></div>
</div>
<div class="MsoNormal" style="line-height: 1.5;"></div>
<div class="MsoNormal" style="line-height: 1.5;"></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="sitemanage"
  style="line-height: 1.5;"
>
  <strong
    ><span style="line-height: 24.5333px; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >13.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >SERVICES MANAGEMENT</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5;"><br /></div>
<div class="MsoNormal" data-custom-class="body_text" style="line-height: 1.5;">
  We reserve the right, but not the obligation, to: (1) monitor the Services for
  violations of these Legal Terms; (2) take appropriate legal action against
  anyone who, in our sole discretion, violates the law or these Legal Terms,
  including without limitation, reporting such user to law enforcement
  authorities; (3) in our sole discretion and without limitation, refuse,
  restrict access to, limit the availability of, or disable (to the extent
  technologically feasible) any of your Contributions or any portion thereof;
  (4) in our sole discretion and without limitation, notice, or liability, to
  remove from the Services or otherwise disable all files and content that are
  excessive in size or are in any way burdensome to our systems; and (5)
  otherwise manage the Services in a manner designed to protect our rights and
  property and to facilitate the proper functioning of the Services.
</div>
<div class="MsoNormal" style="line-height: 1.5;"><br /></div>
<div class="MsoNormal" style="line-height: 1.5;"></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="ppyes"
  style="line-height: 1.5;"
>
  <strong
    ><span style="line-height: 24.5333px; font-size: 19px;"
      ><strong
        ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              >14.</span
            ></strong
          ></span
        ></strong
      >&nbsp;</span
    >PRIVACY POLICY</strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5;"><br /></div>
<div class="MsoNormal" data-custom-class="body_text" style="line-height: 1.5;">
  <span style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
    >We care about data privacy and security. Please review our Privacy
    Policy:<strong
      >&nbsp;<span style="color: rgb(0, 58, 250);"
        ><a
          href="<?=config('app.url')?>/privacy-policy"
          target="_blank"
          data-custom-class="link"
          ><?=config('app.url')?>/privacy-policy</a
        ></span
      ></strong
    >. By using the Services, you agree to be bound by our Privacy Policy, which
    is incorporated into these Legal Terms. Please be advised the Services are
    hosted in the Philippines. If you access the Services from any other region
    of the world with laws or other requirements governing personal data
    collection, use, or disclosure that differ from applicable laws in
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      >the Philippines</span
    >, then through your continued use of the Services, you are transferring
    your data to
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      >the Philippines</span
    >, and you expressly consent to have your data transferred to and processed
    in
    <span
      style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
      >the Philippines</span
    >.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5;"><br /></div>
<div class="MsoNormal" style="line-height: 1.5;"></div>
<div class="MsoNormal" style="line-height: 1.5;"></div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="terms"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 24.5333px; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >15.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >TERM AND TERMINATION</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
    >These Legal Terms shall remain in full force and effect while you use the
    Services. WITHOUT LIMITING ANY OTHER PROVISION OF THESE LEGAL TERMS, WE
    RESERVE THE RIGHT TO, IN OUR SOLE DISCRETION AND WITHOUT NOTICE OR
    LIABILITY, DENY ACCESS TO AND USE OF THE SERVICES (INCLUDING BLOCKING
    CERTAIN IP ADDRESSES), TO ANY PERSON FOR ANY REASON OR FOR NO REASON,
    INCLUDING WITHOUT LIMITATION FOR BREACH OF ANY REPRESENTATION, WARRANTY, OR
    COVENANT CONTAINED IN THESE LEGAL TERMS OR OF ANY APPLICABLE LAW OR
    REGULATION. WE MAY TERMINATE YOUR USE OR PARTICIPATION IN THE SERVICES OR
    DELETE YOUR ACCOUNT AND&nbsp;ANY CONTENT OR INFORMATION THAT YOU POSTED AT
    ANY TIME, WITHOUT WARNING, IN OUR SOLE DISCRETION.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
    >If we terminate or suspend your account for any reason, you are prohibited
    from registering and creating a new account under your name, a fake or
    borrowed name, or the name of any third party, even if you may be acting on
    behalf of the third party. In addition to terminating or suspending your
    account, we reserve the right to take appropriate legal action, including
    without limitation pursuing civil, criminal, and injunctive redress.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="modifications"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 24.5333px; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >16.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >MODIFICATIONS AND INTERRUPTIONS</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
    >We reserve the right to change, modify, or remove the contents of the
    Services at any time or for any reason at our sole discretion without
    notice. However, we have no obligation to update any information on our
    Services. We also reserve the right to modify or discontinue all or part of
    the Services without notice at any time. We will not be liable to you or any
    third party for any modification, price change, suspension, or
    discontinuance of the Services.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
    >We cannot guarantee the Services will be available at all times. We may
    experience hardware, software, or other problems or need to perform
    maintenance related to the Services, resulting in interruptions, delays, or
    errors. We reserve the right to change, revise, update, suspend,
    discontinue, or otherwise modify the Services at any time or for any reason
    without notice to you. You agree that we have no liability whatsoever for
    any loss, damage, or inconvenience caused by your inability to access or use
    the Services during any downtime or discontinuance of the Services. Nothing
    in these Legal Terms will be construed to obligate us to maintain and
    support the Services or to supply any corrections, updates, or releases in
    connection therewith.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="law"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 24.5333px; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >17.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >GOVERNING LAW</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span
    style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
  ></span>
</div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
    >These Legal Terms shall be governed by and defined following the laws of
    the Philippines</span
  >. <?=config('app.name')?> and yourself irrevocably consent that the courts of
  <span style="font-size: 11pt; line-height: 16.8667px; color: rgb(89, 89, 89);"
    >the Philippines</span
  >
  shall have exclusive jurisdiction to resolve any dispute which may arise in
  connection with these Legal Terms.
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="disputes"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 24.5333px; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >18.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >DISPUTE RESOLUTION</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"></div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"></div>
<div
  class="MsoNormal"
  data-custom-class="heading_2"
  style="line-height: 1.5; text-align: left;"
>
  <strong>Informal Negotiations</strong>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 15px;"
    >To expedite resolution and control the cost of any dispute, controversy, or
    claim related to these Legal Terms (each a "Dispute" and collectively, the
    "Disputes") brought by either you or us (individually, a "Party" and
    collectively, the "Parties"), the Parties agree to first attempt to
    negotiate any Dispute (except those Disputes expressly provided below)
    informally for at least thirty (30) days before initiating arbitration. Such
    informal negotiations commence upon written notice from one Party to the
    other Party.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"></div>
<div
  class="MsoNormal"
  data-custom-class="heading_2"
  style="line-height: 1.5; text-align: left;"
>
  <strong>Binding Arbitration</strong>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 15px;"></span>
</div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  Any dispute arising out of or in connection with these Legal Terms, including
  any question regarding its existence, validity, or termination, shall be
  referred to and finally resolved by the International Commercial Arbitration
  Court under the European Arbitration Chamber (Belgium, Brussels, Avenue
  Louise, 146) according to the Rules of this ICAC, which, as a result of
  referring to it, is considered as the part of this clause. The number of
  arbitrators shall be three (3). The seat, or legal place, or arbitration shall
  be Cebu, Philippines. The language of the proceedings shall be English,
  Bisaya. The governing law of these Legal Terms shall be substantive law of the
  Philippines.
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_2"
  style="line-height: 1.5; text-align: left;"
>
  <strong>Restrictions</strong>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  The Parties agree that any arbitration shall be limited to the Dispute between
  the Parties individually. To the full extent permitted by law, (a) no
  arbitration shall be joined with any other proceeding; (b) there is no right
  or authority for any Dispute to be arbitrated on a class-action basis or to
  utilize class action procedures; and (c) there is no right or authority for
  any Dispute to be brought in a purported representative capacity on behalf of
  the general public or any other persons.
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_2"
  style="line-height: 1.5; text-align: left;"
>
  <strong>Exceptions to Informal Negotiations and Arbitration</strong>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  The Parties agree that the following Disputes are not subject to the above
  provisions concerning informal negotiations binding arbitration: (a) any
  Disputes seeking to enforce or protect, or concerning the validity of, any of
  the intellectual property rights of a Party; (b) any Dispute related to, or
  arising from, allegations of theft, piracy, invasion of privacy, or
  unauthorized use; and (c) any claim for injunctive relief. If this provision
  is found to be illegal or unenforceable, then neither Party will elect to
  arbitrate any Dispute falling within that portion of this provision found to
  be illegal or unenforceable and such Dispute shall be decided by a court of
  competent jurisdiction within the courts listed for jurisdiction above, and
  the Parties agree to submit to the personal jurisdiction of that court.
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"></div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="corrections"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >19.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >CORRECTIONS</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  There may be information on the Services that contains typographical errors,
  inaccuracies, or omissions, including descriptions, pricing, availability, and
  various other information. We reserve the right to correct any errors,
  inaccuracies, or omissions and to change or update the information on the
  Services at any time, without prior notice.
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="disclaimer"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 19px; color: rgb(0, 0, 0);"
    ><strong
      ><span style="line-height: 24.5333px; font-size: 19px;"
        ><strong
          ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
            ><strong
              ><span
                style="line-height: 115%; font-family: Arial; font-size: 19px;"
                >20.</span
              ></strong
            ></span
          ></strong
        ></span
      >
      DISCLAIMER</strong
    ></span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span
    style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    >THE SERVICES ARE PROVIDED ON AN AS-IS AND AS-AVAILABLE BASIS. YOU AGREE
    THAT YOUR USE OF THE SERVICES WILL BE AT YOUR SOLE RISK. TO THE FULLEST
    EXTENT PERMITTED BY LAW, WE DISCLAIM ALL WARRANTIES, EXPRESS OR IMPLIED, IN
    CONNECTION WITH THE SERVICES AND YOUR USE THEREOF, INCLUDING, WITHOUT
    LIMITATION, THE IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
    PARTICULAR PURPOSE, AND NON-INFRINGEMENT. WE MAKE NO WARRANTIES OR
    REPRESENTATIONS ABOUT THE ACCURACY OR COMPLETENESS OF THE SERVICES' CONTENT
    OR THE CONTENT OF ANY WEBSITES OR MOBILE APPLICATIONS LINKED TO THE SERVICES
    AND WE WILL ASSUME NO LIABILITY OR RESPONSIBILITY FOR ANY (1) ERRORS,
    MISTAKES, OR INACCURACIES OF CONTENT AND MATERIALS, (2) PERSONAL INJURY OR
    PROPERTY DAMAGE, OF ANY NATURE WHATSOEVER, RESULTING FROM YOUR ACCESS TO AND
    USE OF THE SERVICES, (3) ANY UNAUTHORIZED ACCESS TO OR USE OF OUR SECURE
    SERVERS AND/OR ANY AND ALL PERSONAL INFORMATION AND/OR FINANCIAL INFORMATION
    STORED THEREIN, (4) ANY INTERRUPTION OR CESSATION OF TRANSMISSION TO OR FROM
    THE SERVICES, (5) ANY BUGS, VIRUSES, TROJAN HORSES, OR THE LIKE WHICH MAY BE
    TRANSMITTED TO OR THROUGH THE SERVICES BY ANY THIRD PARTY, AND/OR (6) ANY
    ERRORS OR OMISSIONS IN ANY CONTENT AND MATERIALS OR FOR ANY LOSS OR DAMAGE
    OF ANY KIND INCURRED AS A RESULT OF THE USE OF ANY CONTENT POSTED,
    TRANSMITTED, OR OTHERWISE MADE AVAILABLE VIA THE SERVICES. WE DO NOT
    WARRANT, ENDORSE, GUARANTEE, OR ASSUME RESPONSIBILITY FOR ANY PRODUCT OR
    SERVICE ADVERTISED OR OFFERED BY A THIRD PARTY THROUGH THE SERVICES, ANY
    HYPERLINKED WEBSITE, OR ANY WEBSITE OR MOBILE APPLICATION FEATURED IN ANY
    BANNER OR OTHER ADVERTISING, AND WE WILL NOT BE A PARTY TO OR IN ANY WAY BE
    RESPONSIBLE FOR MONITORING ANY TRANSACTION BETWEEN YOU AND ANY THIRD-PARTY
    PROVIDERS OF PRODUCTS OR SERVICES. AS WITH THE PURCHASE OF A PRODUCT OR
    SERVICE THROUGH ANY MEDIUM OR IN ANY ENVIRONMENT, YOU SHOULD USE YOUR BEST
    JUDGMENT AND EXERCISE CAUTION WHERE APPROPRIATE.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="liability"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >21.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >LIMITATIONS OF LIABILITY</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span
    style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    ><span data-custom-class="body_text"
      >IN NO EVENT WILL WE OR OUR DIRECTORS, EMPLOYEES, OR AGENTS BE LIABLE TO
      YOU OR ANY THIRD PARTY FOR ANY DIRECT, INDIRECT, CONSEQUENTIAL, EXEMPLARY,
      INCIDENTAL, SPECIAL, OR PUNITIVE DAMAGES, INCLUDING LOST PROFIT, LOST
      REVENUE, LOSS OF DATA, OR OTHER DAMAGES ARISING FROM YOUR USE OF THE
      SERVICES, EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH
      DAMAGES.</span
    >
    <span data-custom-class="body_text"
      >NOTWITHSTANDING ANYTHING TO THE CONTRARY CONTAINED HEREIN, OUR LIABILITY
      TO YOU FOR ANY CAUSE WHATSOEVER AND REGARDLESS OF THE FORM OF THE ACTION,
      WILL AT ALL TIMES BE LIMITED TO THE LESSER OF THE AMOUNT PAID, IF ANY, BY
      YOU TO US OR PHP 5000 LESS<span
        style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
        ><span data-custom-class="body_text">.</span>&nbsp;</span
      ><span data-custom-class="body_text"
        >CERTAIN US STATE LAWS AND INTERNATIONAL LAWS DO NOT ALLOW LIMITATIONS
        ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES.
        IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE ABOVE DISCLAIMERS OR
        LIMITATIONS MAY NOT APPLY TO YOU, AND YOU MAY HAVE ADDITIONAL
        RIGHTS.</span
      ></span
    ><span data-custom-class="body_text"></span
  ></span>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="indemnification"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >22.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >INDEMNIFICATION</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span
    style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    >You agree to defend, indemnify, and hold us harmless, including our
    subsidiaries, affiliates, and all of our respective officers, agents,
    partners, and employees, from and against any loss, damage, liability,
    claim, or demand, including reasonable attorneys’ fees and expenses, made by
    any third party due to or arising out of: (1) your
    Contributions;&nbsp;(<span style="font-size: 14.6667px;">2</span>) use of
    the Services; (<span style="font-size: 14.6667px;">3</span>) breach of these
    Legal Terms; (<span style="font-size: 14.6667px;">4</span>) any breach of
    your representations and warranties set forth in these Legal Terms; (<span
      style="font-size: 14.6667px;"
      >5</span
    >) your violation of the rights of a third party, including but not limited
    to intellectual property rights; or (<span style="font-size: 14.6667px;"
      >6</span
    >) any overt harmful act toward any other user of the Services with whom you
    connected via the Services. Notwithstanding the foregoing, we reserve the
    right, at your expense, to assume the exclusive defense and control of any
    matter for which you are required to indemnify us, and you agree to
    cooperate, at your expense, with our defense of such claims. We will use
    reasonable efforts to notify you of any such claim, action, or proceeding
    which is subject to this indemnification upon becoming aware of it.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="userdata"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >23.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >USER DATA</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span
    style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    >We will maintain certain data that you transmit to the Services for the
    purpose of managing the performance of the Services, as well as data
    relating to your use of the Services. Although we perform regular routine
    backups of data, you are solely responsible for all data that you transmit
    or that relates to any activity you have undertaken using the Services. You
    agree that we shall have no liability to you for any loss or corruption of
    any such data, and you hereby waive any right of action against us arising
    from any such loss or corruption of such data.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="electronic"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >24.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >ELECTRONIC COMMUNICATIONS, TRANSACTIONS, AND SIGNATURES</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span
    style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    >Visiting the Services, sending us emails, and completing online forms
    constitute electronic communications. You consent to receive electronic
    communications, and you agree that all agreements, notices, disclosures, and
    other communications we provide to you electronically, via email and on the
    Services, satisfy any legal requirement that such communication be in
    writing. YOU HEREBY AGREE TO THE USE OF ELECTRONIC SIGNATURES, CONTRACTS,
    ORDERS, AND OTHER RECORDS, AND TO ELECTRONIC DELIVERY OF NOTICES, POLICIES,
    AND RECORDS OF TRANSACTIONS INITIATED OR COMPLETED BY US OR VIA THE
    SERVICES. You hereby waive any rights or requirements under any statutes,
    regulations, rules, ordinances, or other laws in any jurisdiction which
    require an original signature or delivery or retention of non-electronic
    records, or to payments or the granting of credits by any means other than
    electronic means.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span style="font-size: 15px;"></span>
</div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="sms"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 19px;"><strong>25. SMS TEXT MESSAGING</strong></span>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span style="font-size: 15px;"></span>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span style="font-size: 15px;"><br /></span>
</div>
<div
  class="MsoNormal"
  data-custom-class="heading_2"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 15px;"><strong>Message Frequency</strong></span>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span style="font-size: 15px;"><br /></span>
</div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 15px;"
    >Not more than 30 SMS per month that is sum of OTP login and purchase
    verification.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span style="font-size: 15px;"></span>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_2"
  style="line-height: 1.5; text-align: left;"
>
  <strong><span style="font-size: 15px;">Opting Out</span></strong>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span style="font-size: 15px;"><br /></span>
</div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 15px;"
    ><span style="font-size: 15px;"
      >If at any time you wish to stop receiving SMS messages from us, simply
      reply to the text with "STOP.” You may receive an SMS message confirming
      your opt out.</span
    ></span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_2"
  style="line-height: 1.5; text-align: left;"
>
  <strong><span style="font-size: 15px;">Message and Data Rates</span></strong>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 15px;"
    >Please be aware that message and data rates may apply to any SMS messages
    sent or received. The rates are determined by your carrier and the specifics
    of your mobile plan.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_2"
  style="line-height: 1.5; text-align: left;"
>
  <strong><span style="font-size: 15px;">Support</span></strong>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 15px;"
    >If you have any questions or need assistance regarding our SMS
    communications, please email us at {{ AppVarHelper::get('business_email', 'info@iprotek.net') }} or call at
    {{ AppVarHelper::get('business_mobile', '09081703461') }}.<br
  /></span>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span style="font-size: 15px;"></span>
</div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="misc"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 115%; font-family: Arial; font-size: 19px;"
      ><strong
        ><span style="line-height: 24.5333px; font-size: 19px;"
          ><strong
            ><span
              style="line-height: 115%; font-family: Arial; font-size: 19px;"
              ><strong
                ><span
                  style="line-height: 115%; font-family: Arial; font-size: 19px;"
                  >26.</span
                ></strong
              ></span
            ></strong
          ></span
        >&nbsp;</strong
      >MISCELLANEOUS</span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span
    style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    >These Legal Terms and any policies or operating rules posted by us on the
    Services or in respect to the Services constitute the entire agreement and
    understanding between you and us. Our failure to exercise or enforce any
    right or provision of these Legal Terms shall not operate as a waiver of
    such right or provision. These Legal Terms operate to the fullest extent
    permissible by law. We may assign any or all of our rights and obligations
    to others at any time. We shall not be responsible or liable for any loss,
    damage, delay, or failure to act caused by any cause beyond our reasonable
    control. If any provision or part of a provision of these Legal Terms is
    determined to be unlawful, void, or unenforceable, that provision or part of
    the provision is deemed severable from these Legal Terms and does not affect
    the validity and enforceability of any remaining provisions. There is no
    joint venture, partnership, employment or agency relationship created
    between you and us as a result of these Legal Terms or use of the Services.
    You agree that these Legal Terms will not be construed against us by virtue
    of having drafted them. You hereby waive any and all defenses you may have
    based on the electronic form of these Legal Terms and the lack of signing by
    the parties hereto to execute these Legal Terms.</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;">
  <span style="font-size: 15px;"></span>
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="heading_1"
  id="contact"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span style="line-height: 115%; font-family: Arial;"
      ><span style="font-size: 19px;"
        ><strong
          ><span style="line-height: 24.5333px; font-size: 19px;"
            ><strong
              ><span
                style="line-height: 115%; font-family: Arial; font-size: 19px;"
                ><strong
                  ><span
                    style="line-height: 115%; font-family: Arial; font-size: 19px;"
                    >27.</span
                  ></strong
                ></span
              ></strong
            ></span
          >&nbsp;</strong
        >CONTACT US</span
      ></span
    ></strong
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span
    style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    >In order to resolve a complaint regarding the Services or to receive
    further information regarding use of the Services, please contact us
    at:</span
  >
</div>
<div class="MsoNormal" style="line-height: 1.5; text-align: left;"><br /></div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <span style="font-size: 15px;"
    ><span style="color: rgb(89, 89, 89);"
      ><strong>{{   AppVarHelper::get('business_name',  config('app.name') )  }}</strong><strong></strong></span
  ></span>
</div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      ><strong>
ADDRESS: {{ AppVarHelper::get('business_address', 'Alegria, Cordova, Cebu, Philippines 66017' ) }}
    </strong></span
    >&nbsp;</strong
  >
</div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      ><strong>Phone:{{ AppVarHelper::get('business_mobile', '09081703461') }}</strong></span
    >&nbsp;</strong
  >
</div>
<div
  class="MsoNormal"
  data-custom-class="body_text"
  style="line-height: 1.5; text-align: left;"
>
  <strong
    ><span
      style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
      ><strong></strong></span
  ></strong>
</div>
<strong
  ><span
    style="font-size:11.0pt;line-height:115%;font-family:Arial;
Calibri;color:#595959;mso-themecolor:text1;mso-themetint:166;"
    ><strong>{{ AppVarHelper::get('business_email', 'info@iprotek.net') }}</strong></span
  ></strong
>
