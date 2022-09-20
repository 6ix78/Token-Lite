@include('Website.header')

<style>
    .file_upload input {
        position: absolute;
        padding: 0 0;
        height: 21px;
        display: flex;
        align-items: center;
        line-height: 11px;
        border-radius: 50px;
        color: #fff;
        margin-left: 71px;
        z-index: 0;
    }

    .form-main .comman_btns {
        border: 0 !important;
        outline: 0;
        cursor: pointer;
        z-index: 9;
        position: relative;
        margin-left: 2px;
    }
</style>
<section class="comman_banner contact_banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="banner_img text-center">
                    <img src="website/assets/images/embassador.png" alt="">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="comman_banner_content">
                    <h1>Join the ArchAngels</h1>
                    <strong class="d-block mt-md-3 mt-2">ArchAngels get compensated as role models <br>within the OSIS community.</strong>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="solutions contact_us_section_main same_bg pb-lg-5">
    <div class="container py-4">
        <div class="row solutions_outer mt-lg-3">
            <div class="col-md-6 my-md-0 my-4 broder-right">
                <div class="solutions_box text-center">
                    <span class="img_part">
                        <img src="website/assets/images/contact_icon.png" alt="">
                    </span>
                    <p class="mb-0 mt-4 text-white">Why should you join our team?<br>ArchAngels get a special position in the OSIS Community, with access to Titans, resources, and earn every time they share the platform.</p>
                    
                </div>
            </div>
            <div class="col-md-6 my-md-0 my-4">
                <div class="solutions_box text-center">
                    <span class="img_part">
                        <img src="website/assets/images/contact-icon1.png" alt="">
                    </span>
                    <p class="mb-0 mt-4 text-white">Role of an ArchAngel<br> 
                            The role of an ArchAngel is to bring new people into this new wave of investing. Apply below to qualify & our team will reach out shortly.</p>
                    
                </div>
            </div>
        </div>
    </div>
</section>



<section class="contact_form same_bg py-5">
    <div class="container pb-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if ($errors->any())
                <div class="alert alert-danger/success/warning" style="font-color: red;">
                    <ul>
                        @foreach ($errors->all() as
                        $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form class="row form-main needs-validation" onsubmit="setDataLayer()" id="partnersForm" novalidate enctype="multipart/form-data" method="post" action="<?php echo action('WebsiteController@partners') ?>">
                    @csrf
                    <div class="form-group col-md-6 mb-4 pb-lg-2">
                        <input type="text" class="form-control" id="validationCustom01" name="fname" placeholder="Full Name" required>
                        <div class="invalid-feedback">
                            Please provide your Full Name.
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-4 pb-lg-2">
                        <input type="text" class="form-control" id="validationCustom02" name="lname" placeholder="Main Social Media Handle" required>
                        <div class="invalid-feedback">
                            Please provide a social media username.
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-4 pb-lg-2">
                        <input type="email" class="form-control" id="validationCustom03" name="email" placeholder="Email" required>
                        <div class="invalid-feedback">
                            Please provide a valid Email.
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-4 pb-lg-2">
                        <input type="text" class="form-control" id="validationCustom04" name="phone" placeholder="Phone" required>
                        <div class="invalid-feedback">
                            Please provide a valid Phone Number.
                        </div>
                    </div>
                    
                    
                    <div class="form-group col-md-12 mb-3 ">
                        <input type="number" class="form-control" id="validationCustom04" name="budget" placeholder="How much will you invest into OSIS?" required>
                        <div class="invalid-feedback">
                            Please provide a valid Investment Amount.
                        </div>
                    </div>
                    
                    
                    <div class="form-group col-md-12 mb-3">
                        <textarea name="message" class="form-control" placeholder="What's your story? Tell us why you're a good fit. Add social media links." id="message" required></textarea>
                        <div class="invalid-feedback">
                            Please provide some professional background info.
                        </div>
                    </div>
                    <div class="form-group col-md-12 file_upload pl-md-5 pl-4 d-flex align-items-center">
                        
                        <input class="" type="file" id="myfile" name="myfile" style="visibility:hidden";>
                        
                        <label class="comman_btns rounded fs-12" for="myfile">Upload File</label>
                    </div>
                    <div class="col-12 text-center mt-md-4 mt-2">
                        <button class="comman_btns border-0" type="submit">APPLY</button>
                    </div>
                </form>
                <script>
        
                function setDataLayer(){
                    let form = new FormData(document.forms.partnersForm);
                    dataLayer.push({
                        event: 'partners-form-submit',
                        em: form.get('email'),
                        fn: form.get('fname').split(' ')[0],
                        ln: form.get('fname').split(' ')[1],
                        phone: form.get('phone')
                    })
                }
                </script>
            </div>
        </div>
    </div>
</section>

<!--<section class="solutions contact_us_section_main same_bg pb-lg-5">-->
<!--    <div class="container py-4">-->
<!--        <div class="row solutions_outer mt-lg-3">-->
<!--            <div class="col-md-6 my-md-0 my-4 broder-right">-->
<!--                <div class="solutions_box text-center">-->
<!--                    <span class="img_part">-->
<!--                        <img src="website/assets/images/contact_icon.png" alt="">-->
<!--                    </span>-->
<!--                    <p class="mb-0 mt-4 text-white">Did you know? We have a referral program.<br>You can earn 10% for each person you refer to the OSIS Pre-Sale.</p>-->
<!--                    <a href="https://osis.world/register" class="comman_btns mt-3 rounded">Sign Up</a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-6 my-md-0 my-4">-->
<!--                <div class="solutions_box text-center">-->
<!--                    <span class="img_part">-->
<!--                        <img src="website/assets/images/contact-icon1.png" alt="">-->
<!--                    </span>-->
<!--                    <p class="mb-0 mt-4 text-white">If you&apos;re a business owner or entrepreneur, <br> you can learn more about launching <br> your own Publicly Traded Brand here. </p>-->
<!--                    <a href="become-a-ptb.php" class="comman_btns mt-3 rounded">Learn More</a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->

<section class="faq same_bg py-5">
    <div class="container py-lg-4">
        <div class="row">
            <div class="col-12 sections_header text-center mb-5">
                <h2>FAQ</h2>
            </div>
            <div class="col-12 mt-lg-4">
                <div class="row justify-content-center">
                    <div class="col-lg-11 d-flex flex-column justify-content-center align-items-stretch">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item mb-lg-4 mb-md-3 mb-3">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Who can invest through OSIS?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse border-0 collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="accordion-body border-0">
                                        <p class="mb-0 fs-14">Anyone in the world can invest on our platform. We accept all major currencies &
                                            digital currencies. Investors can use their debit, credit, or PayPal to make a deposit.
                                            There are certain limits to the amount you can invest depending on your country.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-lg-4 mb-md-3 mb-3">
                                <h2 class="accordion-header" id="headingtwo">
                                    <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapsetwo" aria-expanded="true" aria-controls="collapsetwo">
                                        What is a Token?
                                    </button>
                                </h2>
                                <div id="collapsetwo" class="accordion-collapse border-0 collapse" aria-labelledby="headingtwo" data-parent="#accordionExample">
                                    <div class="accordion-body border-0">
                                        <p class="mb-0 fs-14">A token is a digital representation of security, right, or utility. A token can represent the share capital of a company, the right to collect a portion of a loan, etc. It is registered on
                                            the Web3 and ensures that all token transactions that occur between people or
                                            entities are recorded.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-lg-4 mb-md-3 mb-3">
                                <h2 class="accordion-header" id="headingthree">
                                    <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapsethree" aria-expanded="true" aria-controls="collapsethree">
                                        Are Security Token Offerings Legal?
                                    </button>
                                </h2>
                                <div id="collapsethree" class="accordion-collapse border-0 collapse" aria-labelledby="headingthree" data-parent="#accordionExample">
                                    <div class="accordion-body border-0">
                                        <p class="mb-0 fs-14">Yes, they are, and they must be compliant with the laws of the country where they are
                                            issued. OSIS studies the legality of each project before making an issuance.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-lg-4 mb-md-3 mb-3">
                                <h2 class="accordion-header" id="headingfour">
                                    <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="true" aria-controls="collapsefour">
                                        How do I get started?
                                    </button>
                                </h2>
                                <div id="collapsefour" class="accordion-collapse border-0 collapse" aria-labelledby="headingfour" data-parent="#accordionExample">
                                    <div class="accordion-body border-0">
                                        <p class="mb-0 fs-14">Create an account in 1 minute to access the platform by <a class="text-white" target="_blank" href="https://www.osis.world/login">going here</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-lg-4 mb-md-3 mb-3">
                                <h2 class="accordion-header" id="headingfive">
                                    <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapsefive" aria-expanded="true" aria-controls="collapsefive">
                                        How do I earn money on this platform?
                                    </button>
                                </h2>
                                <div id="collapsefive" class="accordion-collapse border-0 collapse" aria-labelledby="headingfive" data-parent="#accordionExample">
                                    <div class="accordion-body border-0">
                                        <p class="mb-0 fs-14">When you invest through OSIS, you can earn money in two major ways
                                        </p>
                                        <ul class="mt-3">
                                            <li>
                                                <p class="mb-0 fs-14">1. Your tokens go up in price as the Publicly Traded Brand grows & garners a
                                                    higher valuation. You are free to sell your tokens on the OSIS market or other
                                                    decentralized exchanges for a profit.
                                                </p>
                                            </li>
                                            <li>
                                                <p class="mb-0 fs-14">2. If the Publicly Traded Brand you invested into issues dividends, then you will
                                                    share in the profits.
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-lg-4 mb-md-3 mb-3">
                                <h2 class="accordion-header" id="headingsix">
                                    <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapsesix" aria-expanded="true" aria-controls="collapsesix">
                                        What is a PTB?
                                    </button>
                                </h2>
                                <div id="collapsesix" class="accordion-collapse border-0 collapse" aria-labelledby="headingsix" data-parent="#accordionExample">
                                    <div class="accordion-body border-0">
                                        <p class="mb-3 fs-14">A Publicly Traded Brand (PTB) is similar to a publicly-traded company on the stock
                                            market, like Apple, Google, or Tesla. You can create your own PTB using the Apotheosis
                                            Platform. We mint your brand's digital currency which is a completely unique token on
                                            the global Web3; once we list your currency on the open market, your brand is more
                                            accessible than buying into a Fortune 500 company.
                                        </p>
                                        <p class="mb-0 fs-14">Being actively traded on a public exchange, your brand & token have unlimited upside &
                                            will grow as you grow. This concept is able to capture the emotional quality of your
                                            business, brand, and self.

                                        </p>
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-item mb-lg-4 mb-md-3 mb-3">
                                <h2 class="accordion-header" id="headingseven">
                                    <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseseven" aria-expanded="true" aria-controls="collapseseven">
                                        How does it work?
                                    </button>
                                </h2>
                                <div id="collapseseven" class="accordion-collapse border-0 collapse" aria-labelledby="headingseven" data-parent="#accordionExample">
                                    <div class="accordion-body border-0">
                                        <p class="mb-3 fs-14">The Apotheosis Platform is built on a secure Web3 network, some call it Internet
                                            3.0. People, brands & companies can essentially tokenize their business with our
                                            technology. This means they can take something (anything) and split its ownership into a
                                            finite number of tokens; similar to shares in a company.
                                        </p>
                                        <p class="mb-3 fs-14">Now, take a house for example. Normally, one or a few people purchase a house & it
                                            costs them hundreds of thousands. With tokenization, the ownership of this house can
                                            be split into thousands of pieces, be sold to thousands of buyers, and each person
                                            would own a piece of real estate for only a few hundred dollars. These buyers can then
                                            benefit from the rental income the house generates and are also free to sell their piece
                                            at any time on the open market.
                                        </p>
                                        <p class="mb-3 fs-14">The same is true for a business. Split your company into thousands of pieces, allow
                                            investors to come in at a low price, and get access to capital faster from an international
                                            investment pool. All of this is done securely through the Web3; as security &
                                            legitimacy is the foundation of the technology. As an investor, you truly own a share of
                                            the house and the business you buy into. As a seller, you receive real dollars in
                                            exchange for tokenized pieces of your business or assets.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-lg-4 mb-md-3 mb-3">
                                <h2 class="accordion-header" id="headingeight">
                                    <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseeight" aria-expanded="true" aria-controls="collapseeight">
                                        Why should I invest in OSIS?
                                    </button>
                                </h2>
                                <div id="collapseeight" class="accordion-collapse border-0 collapse" aria-labelledby="headingthree" data-parent="#accordionExample">
                                    <div class="accordion-body border-0">
                                        <p class="mb-3 fs-14">1. An investment into the initial OSIS platform is an investment into every possibility that can come from it. This means every brilliant person, brand, business, real estate, fine
                                            artwork, idea, etc. Every single Publicly Traded Brand that people create here, we
                                            capture that value in the original platform's token.
                                        </p>
                                        <p class="mb-3 fs-14">This is like investing in Coca-Cola before it expanded to acquire Mountain Dew & the
                                            hundreds of other famous beverages we all enjoy. The sum total value of all these other
                                            beverages is captured within the Coca-Cola brand and divided into the net worths of its
                                            investors
                                        </p>
                                        <p class="mb-3 fs-14">2. You are investing in a movement to create a free and fair global economy. This
                                            platform is a first of its kind, liberating people from a system that does not value them or
                                            what society truly wants this world to become. Apotheosis is raising funds to create the
                                            technology to make this a reality & give everyone the means to create their own Publicly
                                            Traded Branded.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>


@include('Website.footer')