<div class="wrapper">
  <div class="steps-area steps-area-fixed">
    <div class="image-holder"> <img src="img/side-img.jpg" alt=""> </div>
    <div class="steps clearfix">
      <ul class="tablist multisteps-form__progress">
        <li class="multisteps-form__progress-btn js-active current"> <span>1</span>
          <h3 class="tab-title">Personal Information</h3>
        </li>
        <li class="multisteps-form__progress-btn"> <span>2</span>
          <h3 class="tab-title">Birthing Experiences</h3>
        </li>
        <li class="multisteps-form__progress-btn"> <span>3</span>
          <h3 class="tab-title">Postpartum Diet</h3>
        </li>
        <li class="multisteps-form__progress-btn"> <span>4</span>
          <h3 class="tab-title">Your General Health</h3>
        </li>
        <li class="multisteps-form__progress-btn last"> <span>5</span>
          <h3 class="tab-title">Your Mental Health</h3>
        </li>
      </ul>
    </div>
  </div>
  <form class="multisteps-form__form" action="<?php echo URL('/') ?>/api/submit_html_questinnaires" id="wizard" method="POST">
    <input type="hidden" name="questionnaire_id" value="3" />
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
    <div class="form-area position-relative">
      <!-- div 1 -->
      <div class="multisteps-form__panel js-active" data-animation="slideHorz">
        <div class="wizard-forms">
          <div class="inner pb-100 clearfix">
            <div class="form-content pera-content col-md-10">
              <div class="step-inner-content"> <span class="step-no">POSTNATAL QUESTIONNAIRE</span>
                <p>Please fill out the following questionnaire to help us get to know you and serve you best during our time together. Thank you!</p>
                <h5 class="mt-3 mb-3">Personal Information</h5>
                <section id="step_1">
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Full Name</label>
                      <input type="text" class="form-control" name="full_name" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Age & DOB</label>
                      <input type="date" class="form-control datepicker1" name="dob" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Email Address</label>
                      <input type="text" class="form-control" name="email" />
                    </div>
                  </div>
                  <h5 class="mt-3 mb-3">About your Baby/babies</h5>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Baby’s Name</label>
                      <input type="text" class="form-control" name="baby_name" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Gender</label>
                      <select class="form-control" name="gender">
                        <option>Select</option>
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                      </select>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Date of birth</label>
                      <input type="date" class="form-control datepicker1" name="dob" />
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">Weight at birth [K.G.]</label>
                      <input type="text" class="form-control" name="birth_weight" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Doctor’s Name</label>
                      <input type="text" class="form-control" name="doctorname" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">About your pregnancy/birth/baby <small class="d-block">Please list briefly any medical problems you had during pregnancy (include high blood pressure, back pain etc)</small> </label>
                      <input type="text" class="form-control" name="about_pregnancy" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Please write a short description of your labour <small class="d-block">(include pain relief used, medical intervention etc).</small> </label>
                      <input type="text" class="form-control" name="labour_description" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Are you or your baby presently on any medication? <div class="switch-field">
                          <input type="radio" id="radio-one" name="baby_on_medication" value="yes" checked />
                          <label for="radio-one">Yes</label>
                          <input type="radio" id="radio-two" name="baby_on_medication" value="no" />
                          <label for="radio-two">No</label>
                        </div>Please give details</label>
                      <input type="text" class="form-control" name="baby_on_medication_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Did your baby require any special care<div class="switch-field">
                          <input type="radio" id="radio-one101" name="baby_require_care" value="yes" checked />
                          <label for="radio-one101">Yes</label>
                          <input type="radio" id="radio-two101" name="baby_require_care" value="no" />
                          <label for="radio-two101">No</label>
                        </div> Please give details</label>
                      <input type="text" class="form-control" name="baby_require_care_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Does your baby have any medical condition we should be aware of?</label>
                      <input type="text" class="form-control" name="baby_have_medicatiom" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Please give details of any complications or miscarriages in previous pregnancies and births:</label>
                      <input type="text" class="form-control" name="details_of_complications" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Do you have any specific health problems related to your pregnancy and labour?</label>
                      <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Split abdominal muscles">
                          <label class="checkbox-label">Split abdominal muscles</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Pelvic instability">
                          <label class="checkbox-label">Pelvic instability</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Pain in the pubic bone">
                          <label class="checkbox-label">Pain in the pubic bone</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Constipation">
                          <label class="checkbox-label">Constipation</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="High blood pressure">
                          <label class="checkbox-label">High blood pressure</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Low blood pressure">
                          <label class="checkbox-label">Low blood pressure</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Haemorrhoids">
                          <label class="checkbox-label">Haemorrhoids</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Varicose veins">
                          <label class="checkbox-label">Varicose veins</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Fluid retention">
                          <label class="checkbox-label">Fluid retention</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Back or neck pain">
                          <label class="checkbox-label">Back or neck pain</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Insomnia">
                          <label class="checkbox-label">Insomnia</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="health_problems[]" value="Nausea">
                          <label class="checkbox-label">Nausea </label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="health_problems[]" value="Others">
                          <label class="checkbox-label">Other?</label>
                          <input type="text" class="form-control" name="other_health_problems" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Are you breast-feeding?</label>
                      <input type="text" class="form-control" name="breast-feeding" />
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </div>
          <!-- /.inner -->
          <div class="actions">
            <ul>
              <li class="disable" aria-disabled="true"><span class="js-btn-next" title="NEXT">Backward <i class="fa fa-arrow-right"></i></span></li>
              <li><span class="js-btn-next" title="NEXT">NEXT <i class="fa fa-arrow-right"></i></span></li>
            </ul>
          </div>
        </div>
      </div>
      <!-- div 2 -->
      <div class="multisteps-form__panel" data-animation="slideHorz">
        <div class="wizard-forms">
          <div class="inner pb-100 clearfix">
            <div class="form-content pera-content col-md-10">
              <div class="step-inner-content"> <span class="step-no bottom-line">Step 2</span>
                <h5 class="mt-3 mb-5">Birthing Experiences</h5>
                <section id="step_2">
                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">1. Length of labour in total?</label>
                      <span><input type="text" class="form-control" name="labour_length" style="display:inherit !important; width: 75%;" />&nbsp;hrs</span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">2. Was labour self starting <div class="switch-field">
                          <input type="radio" id="radio-1" name="labourself_starting" value="induced" checked />
                          <label for="radio-1">induced</label>
                          <input type="radio" id="radio-2" name="labourself_starting" value="accelerated" />
                          <label for="radio-2">accelerated?</label>
                        </div></label>
                      <!-- <input type="text" class="form-control" name="full_name"/> -->
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">3. Nature of delivery <div class="switch-field">
                          <input type="radio" id="radio-11" name="nature_of_delivery" value="vaginal" checked />
                          <label for="radio-11">vaginal</label>
                          <input type="radio" id="radio-21" name="nature_of_delivery" value="ventouse forceps" />
                          <label for="radio-21">ventouse forceps</label>
                          <input type="radio" id="radio-31" name="nature_of_delivery" value="caesarean" />
                          <label for="radio-31">caesarean</label>
                        </div></label>
                      <input type="text" class="form-control" name="nature_of_delivery_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">4. Delivery environment

                        <div class="switch-field">
                          <input type="radio" id="radio-13" name="delivery_environment" value="hospital" checked />
                          <label for="radio-13">hospital</label>
                          <input type="radio" id="radio-23" name="delivery_environment" value="home" />
                          <label for="radio-23">home</label>
                          <input type="radio" id="radio-33" name="delivery_environment" value="water birth" />
                          <label for="radio-33">water birth</label>
                          <input type="radio" id="radio-43" name="delivery_environment" value="other" />
                          <label for="radio-43">other</label>
                        </div>
                      </label>
                      <!-- <input type="text" class="form-control" name="full_name"/> -->
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">5. Any drugs administered during labour:

                        <div class="switch-field">
                          <input type="radio" id="radio-14" name="drugs_administered" value="gas and air" checked />
                          <label for="radio-14"> gas and air</label>
                          <input type="radio" id="radio-24" name="drugs_administered" value="pethidine" />
                          <label for="radio-24">pethidine</label>
                          <input type="radio" id="radio-34" name="drugs_administered" value="epidural" />
                          <label for="radio-34">epidural</label>
                          <input type="radio" id="radio-44" name="drugs_administered" value="other" />
                          <label for="radio-44">other</label>
                        </div>
                      </label>
                      <!-- <input type="text" class="form-control" name="full_name"/> -->
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">6. Any damage to perineum suffered.

                        <div class="switch-field">
                          <input type="radio" id="radio-15" name="damage_to_perineum" value="Yes" checked />
                          <label for="radio-15">Yes</label>
                          <input type="radio" id="radio-25" name="damage_to_perineum" value="No" />
                          <label for="radio-25">No</label>
                        </div>
                      </label>
                      <!-- <input type="text" class="form-control" name="full_name"/> -->
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">7. Any stitches required following tearing / episiotomy?

                        <div class="switch-field">
                          <input type="radio" id="radio-16" name="stitches_required" value="Yes" checked />
                          <label for="radio-16">Yes</label>
                          <input type="radio" id="radio-26" name="stitches_required" value="No" />
                          <label for="radio-26">No</label>
                        </div>
                      </label>
                      <!-- <input type="text" class="form-control" name="full_name"/> -->
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">8. Any postpartum hemorrhage?

                        <div class="switch-field">
                          <input type="radio" id="radio-17" name="postpartum_hemorrhage" value="yes" checked />
                          <label for="radio-17">Yes</label>
                          <input type="radio" id="radio-27" name="postpartum_hemorrhage" value="no" />
                          <label for="radio-27">No</label>
                        </div>
                      </label>
                      <!-- <input type="text" class="form-control" name="full_name"/> -->
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">9. Was your baby:

                        <div class="switch-field">
                          <input type="radio" id="radio-18" name="Wasyourbaby" value="full term" checked />
                          <label for="radio-18">full term</label>
                          <input type="radio" id="radio-28" name="Wasyourbaby" value="premature" />
                          <label for="radio-28">premature</label>
                          <input type="radio" id="radio-38" name="Wasyourbaby" value="overdue" />
                          <label for="radio-38">overdue</label>
                        </div>
                      </label>
                      <!-- <input type="text" class="form-control" name="full_name"/> -->
                    </div>
                  </div>
                  <!-- <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">10. At what stage was the umbilical cord cut?</label>
                      <input type="text" class="form-control" name="full_name"/>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">11. Stage of health of baby at and immediately after birth?</label>
                      <input type="text" class="form-control" name="full_name"/>
                    </div>
                  </div> -->
                </section>
              </div>
            </div>
          </div>
          <!-- /.inner -->
          <div class="actions">
            <ul>
              <li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK </span></li>
              <li><span class="js-btn-next" title="NEXT">NEXT <i class="fa fa-arrow-right"></i></span></li>
            </ul>
          </div>
        </div>
      </div>
      <!-- div 3 -->
      <div class="multisteps-form__panel" data-animation="slideHorz">
        <div class="wizard-forms">
          <div class="inner pb-100 clearfix">
            <div class="form-content pera-content col-md-10">
              <div class="step-inner-content"> <span class="step-no bottom-line">Step 3</span>
                <h5 class="mt-3 mb-3">Postpartum Diet</h5>
                <section id="step_3">
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label form-inner-area">How many times do you eat each day? Meals
                        <select name="times_eatMeals" style="width:100px;background-position-y: 9px !important;">
                          <option selected>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                          <option>6</option>
                          <option>7</option>
                          <option>8</option>
                          <option>9</option>
                          <option>10</option>
                        </select>
                        Snacks
                        <select name="times_eatSnacks" style="width:100px;background-position-y: 9px !important;">
                          <option selected>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                          <option>6</option>
                          <option>7</option>
                          <option>8</option>
                          <option>9</option>
                          <option>10</option>
                        </select>
                      </label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="checkbox-label">Are there any foods or beverages that you cannot or will not eat?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one7" name="foods_cannot_eat" value="yes" checked />
                        <label for="radio-one7">Yes</label>
                        <input type="radio" id="radio-two7" name="foods_cannot_eat" value="no" />
                        <label for="radio-two7">No</label>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Yes, please list</label>
                      <input type="text" class="form-control" name="foods_cannot_eat_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="checkbox-label">Are there any foods of which you think you do not eat enough?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one8" name="foods_cannot_eat_enough" value="yes" checked />
                        <label for="radio-one8">Yes</label>
                        <input type="radio" id="radio-two8" name="foods_cannot_eat_enough" value="no" />
                        <label for="radio-two8">No</label>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Yes, please list</label>
                      <input type="text" class="form-control" name="foods_cannot_eat_enough_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">What do you usually drink? (Please check all that apply.)
                        <div class="switch-field">
                          <input type="radio" id="radio-19" name="drink" value="Milk" checked />
                          <label for="radio-19">Milk</label>
                          <input type="radio" id="radio-29" name="drink" value="Water" />
                          <label for="radio-29">Water</label>
                          <input type="radio" id="radio-39" name="drink" value="Juice/Juice Drinks" />
                          <label for="radio-39">Juice/Juice Drinks</label>
                          <input type="radio" id="radio-49" name="drink" value="Gatorade/Sports" />
                          <label for="radio-49">Gatorade/Sports</label>
                          <input type="radio" id="radio-59" name="drink" value="Drinks Coffee/Tea Herbal" />
                          <label for="radio-59">Drinks Coffee/Tea Herbal</label>
                          <input type="radio" id="radio-69" name="drink" value="Other" />
                          <label for="radio-69">Other</label>
                        </div>
                      </label>
                      <div class="clearfix">
                        <input type="text" class="form-control" name="" />
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">How often do you drink milk?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-111" name="drinkmilk" value="Several times/day" checked />
                        <label for="radio-111">Several times/day</label>
                        <input type="radio" id="radio-211" name="drinkmilk" value="Once/day" />
                        <label for="radio-211">Once/day</label>
                        <input type="radio" id="radio-311" name="drinkmilk" value="Less than once/day" />
                        <label for="radio-311">Less than once/day</label>
                        <input type="radio" id="radio-411" name="drinkmilk" value="Do not drink milk" />
                        <label for="radio-411">Do not drink milk</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="checkbox-label">How many times do you eat fruits and vegetables during a normal day?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-112" name="fruitsandvegetables" value="4 times/day" checked />
                        <label for="radio-112">4 times/day </label>
                        <input type="radio" id="radio-212" name="fruitsandvegetables" value="Twice/day" />
                        <label for="radio-212">Twice/day</label>
                        <input type="radio" id="radio-312" name="fruitsandvegetables" value="Once/day" />
                        <label for="radio-312">Once/day </label>
                        <input type="radio" id="radio-412" name="fruitsandvegetables" value="Do not eat any fruits or vegetables" />
                        <label for="radio-412">Do not eat any fruits or vegetables </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Which protein foods do you usually eat? (Please check all that apply.)</label>
                      <ul class="list-inline-display clearfix">
                        <li>
                          <input type="checkbox" name="proteinfoods[]" value="Chicken/Turkey">
                          <label class="checkbox-label">Chicken/Turkey</label>
                        </li>
                        <li>
                          <input type="checkbox" name="proteinfoods[]" value="Fish/Seafood">
                          <label class="checkbox-label">Fish/Seafood</label>
                        </li>
                        <li>
                          <input type="checkbox" name="proteinfoods[]" value="Lamb/Meat">
                          <label class="checkbox-label">Lamb/Meat </label>
                        </li>
                        <li>
                          <input type="checkbox" name="proteinfoods[]" value="Eggs">
                          <label class="checkbox-label">Eggs</label>
                        </li>
                        <li>
                          <input type="checkbox" name="proteinfoods[]" value="Tofu/Paneer">
                          <label class="checkbox-label">Tofu/Paneer</label>
                        </li>
                        <li>
                          <input type="checkbox" name="proteinfoods[]" value="Yogurt Soft Cheese">
                          <label class="checkbox-label">Yogurt Soft Cheese</label>
                        </li>

                      </ul>
                      <div class="clearfix">
                        <input type="text" class="form-control" name="" />
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="checkbox-label">Are you on a special diet or trying to lose weight?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one2" name="specialdiet" value="yes" checked />
                        <label for="radio-one2">Yes</label>
                        <input type="radio" id="radio-two2" name="specialdiet" value="no" />
                        <label for="radio-two2">No</label>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Yes, please describe</label>
                      <input type="text" class="form-control" name="specialdiet_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="checkbox-label">Do you have any medical/health/dental problems?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one3" name="medicalhealthdental" value="yes" checked />
                        <label for="radio-one3">Yes</label>
                        <input type="radio" id="radio-two3" name="medicalhealthdental" value="no" />
                        <label for="radio-two3">No</label>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Yes, please list</label>
                      <input type="text" class="form-control" name="medicalhealthdental_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="checkbox-label">Was this problem diagnosed by a doctor / dentist?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one4" name="medicalhealthdental_diagnosed" value="yes" checked />
                        <label for="radio-one4">Yes</label>
                        <input type="radio" id="radio-two4" name="medicalhealthdental_diagnosed" value="no" />
                        <label for="radio-two4">No</label>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Yes, please describe</label>
                      <input type="text" class="form-control" name="medicalhealthdental_diagnosed_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="checkbox-label">Please check and describe all of the following you routinely use. (All information given to the Prajanna wellness Program is confidential.)</label>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 mt-2">
                      <label class="control-label">Over-the-counter drugs (laxatives, pain killers, etc.)</label>
                      <input type="text" class="form-control" name="counterdrugs" />
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 mt-3">
                      <label class="control-label">Prescription medication</label>
                      <input type="text" class="form-control" name="Prescriptionmedication" />
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 mt-3">
                      <label class="control-label">Vitamin and/or minerals supplements</label>
                      <input type="text" class="form-control" name="Vitamin" />
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 mt-3">
                      <label class="control-label">Herbs/Herbal Supplements (Echinacea, ginger, etc.)</label>
                      <input type="text" class="form-control" name="Herbal" />
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 mt-3">
                      <label class="control-label">Other</label>
                      <input type="text" class="form-control" name="Other_routine" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="checkbox-label">How much did you weigh before your pregnancy that just ended? </label>
                      <input type="text" class="form-control" name="weigh" style="width: 80%!important; display:initial !important" />Kgs
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Are you experiencing any of the following situations?</label>
                      <ul class="list-inline-display clearfix">
                        <li>
                          <input type="checkbox" name="experiencing[]" value="Baby always seems to be hungry">
                          <label class="checkbox-label">Baby always seems to be hungry</label>
                        </li>
                        <li>
                          <input type="checkbox" name="experiencing[]" value="Don’t have enough milk">
                          <label class="checkbox-label">Don’t have enough milk</label>
                        </li>
                        <li>
                          <input type="checkbox" name="experiencing[]" value="Baby refuses breast">
                          <label class="checkbox-label">Baby refuses breast</label>
                        </li>
                        <li>
                          <input type="checkbox" name="experiencing[]" value="Arches back">
                          <label class="checkbox-label">Arches back </label>
                        </li>
                        <li>
                          <input type="checkbox" name="experiencing[]" value="Sore nipples">
                          <label class="checkbox-label">Sore nipples</label>
                        </li>
                        <li>
                          <input type="checkbox" name="experiencing[]" value="Sore breasts">
                          <label class="checkbox-label">Sore breasts</label>
                        </li>
                        <li>
                          <input type="checkbox" name="experiencing[]" value="Engorged or full">
                          <label class="checkbox-label">Engorged or full</label>
                        </li>
                        <li>
                          <input type="checkbox" name="experiencing[]" value="Hard breasts">
                          <label class="checkbox-label">Hard breasts</label>
                        </li>
                        <li>
                          <input type="checkbox" name="experiencing[]" value="Other">
                          <label class="checkbox-label">Other</label>
                        </li>
                      </ul>
                      <div class="clearfix">
                        <input type="text" class="form-control" name="experiencing_details" />
                      </div>
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </div>
          <!-- ./inner -->
          <div class="actions">
            <ul>
              <li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK </span></li>
              <li><span class="js-btn-next" title="NEXT">NEXT <i class="fa fa-arrow-right"></i></span></li>
            </ul>
          </div>
        </div>
      </div>
      <!-- div 4 -->
      <div class="multisteps-form__panel" data-animation="slideHorz">
        <div class="wizard-forms">
          <div class="inner pb-100 clearfix">
            <div class="form-content pera-content col-md-10">
              <div class="step-inner-content"> <span class="step-no bottom-line">Step 4</span>
                <h5 class="mt-3 mb-3">Your General Health</h5>
                <section id="step_4">
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <h6 class="mb-3">Please check any that apply: </h6>
                      <div class="row ">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Chronic Sinus Condition">
                          <label class="checkbox-label">Chronic Sinus Condition</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Heart Trouble">
                          <label class="checkbox-label">Heart Trouble</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Low Blood Pressure">
                          <label class="checkbox-label">Low Blood Pressure</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Diabetes">
                          <label class="checkbox-label">Diabetes</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Hypoglycemia">
                          <label class="checkbox-label">Hypoglycemia</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Genito-urinary Difficulties">
                          <label class="checkbox-label">Genito-urinary Difficulties </label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Asthma">
                          <label class="checkbox-label">Asthma</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Ulcers">
                          <label class="checkbox-label">Ulcers</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Intestinal Complications">
                          <label class="checkbox-label">Intestinal Complications</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Immune Deficiency">
                          <label class="checkbox-label">Immune Deficiency</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Epilepsy">
                          <label class="checkbox-label">Epilepsy</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Chronic Headaches">
                          <label class="checkbox-label">Chronic Headaches </label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Herniated Disc">
                          <label class="checkbox-label">Herniated Disc</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Spondylolisthesis / Spondylolysis ">
                          <label class="checkbox-label">Spondylolisthesis / Spondylolysis </label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Osteoarthritis">
                          <label class="checkbox-label">Osteoarthritis</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Rheumatoid Arthritis">
                          <label class="checkbox-label">Rheumatoid Arthritis</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Hernia">
                          <label class="checkbox-label">Hernia</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="High Blood Pressure">
                          <label class="checkbox-label">High Blood Pressure</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="GeneralHealth[]" value="Recent Surgery">
                          <label class="checkbox-label">Recent Surgery</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 mb-3">
                          <label class="control-label">Explain</label>
                          <input type="text" class="form-control" name="" />
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="diabetes[]" value="hbp">
                          <label class="checkbox-label">Past or present allergies (to what if any?)</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="diabetes[]" value="hbp">
                          <label class="checkbox-label">Chronic Pain</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 mb-5">
                          <label class="control-label">Where?</label>
                          <input type="text" class="form-control" name="ChronicPainWhere" />
                        </div>
                      </div>
                      <!-- <h6>Please describe any other health or medical conditions that you believe may be helpful for us to be aware of:</h6>
                      <h5 class="mt-5">In case of emergency, contact: </h5>
                      <div class="form-group form-inner-area row">
                        <div class="col-md-6 col-lg-6 col-sm-6">
                          <label class="control-label">Name</label>
                          <input type="text" class="form-control" name="full_name"/>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6">
                          <label class="control-label">Phone</label>
                          <input type="text" class="form-control" name="full_name"/>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6">
                          <label class="control-label">Relationship</label>
                          <input type="text" class="form-control" name="full_name"/>
                        </div>
                      </div> -->
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </div>
          <div class="actions">
            <ul>
              <li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK </span></li>
              <li><span class="js-btn-next" title="NEXT">NEXT <i class="fa fa-arrow-right"></i></span></li>
            </ul>
          </div>
        </div>
      </div>
      <!-- div 5 -->
      <div class="multisteps-form__panel" data-animation="slideHorz">
        <div class="wizard-forms">
          <div class="inner pb-100 clearfix">
            <div class="form-content pera-content col-md-10">
              <div class="step-inner-content"> <span class="step-no bottom-line">Step 5</span>
                <h5 class="mt-3 mb-3">Your Mental Health</h5>
                <section id="step_5">
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Do you feel you have suffered depression since the birth of your baby?
                        <div class="switch-field">
                          <input type="radio" id="radio-one35" name="depression" value="yes" checked />
                          <label for="radio-one35">Yes</label>
                          <input type="radio" id="radio-two35" name="depression" value="no" />
                          <label for="radio-two35">No</label>
                        </div>
                      </label>
                      <input type="text datepicker" class="form-control" name="depression_date" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Please state any particular areas, either physical or emotional, that you would like to gain help with from this Wellness program</label>
                      <input type="text datepicker" class="form-control" name="physicaloremotional" />
                    </div>
                  </div>
                  <h5 class="mt-5 mb-5">A little more about you</h5>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <h6 class="mb-3">What is your motivation for taking yoga? Mark all that apply:</h6>
                      <div class="row ">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="motivation[]" value="Pelvic Floor Health">
                          <label class="checkbox-label">Pelvic Floor Health</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="motivation[]" value="Physical Health">
                          <label class="checkbox-label">Physical Health</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="motivation[]" value="Flexibility">
                          <label class="checkbox-label">Flexibility</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="motivation[]" value="Joint Pain">
                          <label class="checkbox-label">Joint Pain</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="motivation[]" value="Stress Reduction">
                          <label class="checkbox-label">Stress Reduction</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="motivation[]" value="Core Strength">
                          <label class="checkbox-label">Core Strength</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="motivation[]" value="Mental Clarity">
                          <label class="checkbox-label">Mental Clarity</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="motivation[]" value="Strength">
                          <label class="checkbox-label">Strength</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="motivation[]" value="General Fitness">
                          <label class="checkbox-label">General Fitness</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 mb-5">
                          <label class="control-label">Other</label>
                          <input type="text" class="form-control" name="motivation_details" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <label class="control-label">Did you practise yoga before your pregnancy and / during your pregnancy?
                            <div class="switch-field">
                              <input type="radio" id="radio-one39" name="practiseyoga" value="yes" checked />
                              <label for="radio-one39">Yes</label>
                              <input type="radio" id="radio-two39" name="practiseyoga" value="no" />
                              <label for="radio-two39">No</label>
                            </div>

                            Please give details of length of time and style
                          </label>
                          <input type="text datepicker" class="form-control" name="practiseyoga_details" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <label class="control-label">Is there any further information you would like to make us aware of?</label>
                          <input type="text datepicker" class="form-control" name="informationaware" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <label class="control-label"><input type="responsibility" name="na" value="NA" style="width: auto !important;
height: auto !important; margin-right:3px !important;">I take full responsibility over the health of my baby & myself in the yoga sessions and should there be any medical change I will consult my physician.</label>
                          <input type="text datepicker" class="form-control" name="responsibility_details" />
                        </div>
                      </div>
                      <h6 class="mt-5 mb-2">Disclaimer :</h6>
                      <p class="control-label"><input type="aware" name="na" value="NA" style="width: auto !important;
height: auto !important; margin-right:3px !important;">As far as I am aware, I have disclosed all information regarding my health relevant to the practice of Post Natal Yoga. I take full responsibility for all applications of Yoga. I may practice outside the classes. I fully understand that the recommendations, ideas or techniques expressed and described in Yoga classes as well as in books and videos cannot be regarded as substitutes for the advice of qualified medical practitioners. Any uses to which the recommendations, ideas and techniques are put are at my sole discretion and risk.</p>
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </div>
          <div class="actions">
            <ul>
              <li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK </span></li>
              <li><button type="submit" title="NEXT">SUBMIT <i class="fa fa-arrow-right"></i></span></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>