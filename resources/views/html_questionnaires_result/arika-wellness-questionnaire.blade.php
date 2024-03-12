<div class="wrapper">
  <div class="steps-area steps-area-fixed">
    <div class="image-holder"> <img src="img/side-img.jpg" alt=""> </div>
    <div class="steps clearfix">
      <ul class="tablist multisteps-form__progress">
        <li class="multisteps-form__progress-btn js-active current"> <span>1</span>
          <h3 class="tab-title">Personal Information</h3>
        </li>
        <li class="multisteps-form__progress-btn"> <span>2</span>
          <h3 class="tab-title">Personal and Family History</h3>
        </li>
        <li class="multisteps-form__progress-btn"> <span>3</span>
          <h3 class="tab-title">Personal Complaints</h3>
        </li>
        <li class="multisteps-form__progress-btn last"> <span>4</span>
          <h3 class="tab-title">Food Habits</h3>
        </li>
      </ul>
    </div>
  </div>
  <form class="multisteps-form__form" action="<?php echo URL('/') ?>/api/submit_html_questinnaires" id="wizard" method="POST">
    <input type="hidden" name="questionnaire_id" value="1" />
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
    <div class="form-area position-relative">
      <!-- div 1 -->
      <div class="multisteps-form__panel js-active" data-animation="slideHorz">
        <div class="wizard-forms">
          <div class="inner pb-100 clearfix">
            <div class="form-content pera-content col-md-10">
              <div class="step-inner-content"> <span class="step-no">Step 1</span>
                <h5>Personal Information</h5>
                <section id="step_1">
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Date</label>
                      <p><?php echo $result['date']; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Full Name</label>
                      <p><?php echo $result['full_name']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Age & DOB</label>
                      <p><?php echo $result['dob']; ?></p>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Gender</label>
                      <p><?php echo $result['gender']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Occupation</label>
                      <p><?php echo $result['occupation']; ?></p>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Address</label>
                      <p><?php echo $result['address']; ?></p>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Phone Number 1</label>
                      <p><?php echo $result['phone_1']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Phone Number 2 (Optional)</label>
                      <p><?php echo $result['phone_2']; ?></p>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Email Address</label>
                      <p><?php echo !empty($result['email']) ?  $result['email'] : ''; ?></p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <h5>Physical Constitution</h5>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                      <label class="control-label">Height (cm)</label>
                      <p><?php echo $result['height']; ?></p>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                      <label class="control-label">Weight (kg)</label>
                      <p><?php echo $result['weight']; ?></p>
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                      <label class="control-label">Arm (inches)</label>
                      <p><?php echo $result['arm']; ?></p>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                      <label class="control-label">Thigh (inches)</label>
                      <p><?php echo $result['thigh']; ?></p>
                    </div>

                  </div>
                  <!-- 
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Arm (inches)</label>
                      <input type="number" class="form-control" name="arm"/>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Thigh (inches)</label>
                      <input type="number" class="form-control" name="thigh"/>
                    </div>
                  </div> -->
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Neck Circumference (inches)</label>
                      <p><?php echo $result['neck_circumference']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Waist (inches)</label>
                      <p><?php echo $result['waist']; ?></p>
                    </div>

                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Hip (inches)</label>
                      <p><?php echo $result['hip']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Waist / Hip Ratio <0.8 (Female) : <1 (Male) </label>
                          <p><?php echo $result['waist_hip_ratio']; ?></p>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <h5>List of Medicines and Supplements</h5>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="table-responeive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Medicines</th>
                              <th>Dosage</th>
                              <th>Supplements</th>
                              <th>Dosage</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($result['medicine']['medicine_name'] as $key =>  $medicine_name) { ?>
                              <tr>
                                <td><?php echo $medicine_name;  ?></td>
                                <td><?php echo $result['medicine']['medicine_dosage'][$key]; ?></td>
                                <td><?php echo $result['medicine']['supplement_name'][$key]; ?></td>
                                <td><?php echo $result['medicine']['supplement_dosage'][$key]; ?></td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
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
                <h5 class="mt-3 mb-3">Personal and Family History</h5>
                <section id="step_2">
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <h5>Chielf Complaints/Major Concerns</h5>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <div class="row ">
                        <?php
                        if (!empty($result['cheif_complaints'])) {
                          foreach ($result['cheif_complaints'] as $cheif_complaint) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $cheif_complaint; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <h5>Family History</h5>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Diabetes</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['Diabetes'])) {
                          foreach ($result['family_history']['Diabetes'] as $Diabetes) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $Diabetes; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>HBP</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['HBP'])) {
                          foreach ($result['family_history']['HBP'] as $HBP) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $HBP; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>H Lipid</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['HLipid'])) {
                          foreach ($result['family_history']['HLipid'] as $HLipid) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $HLipid; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Anaemia</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['Anaemia'])) {
                          foreach ($result['family_history']['Anaemia'] as $Anaemia) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $Anaemia; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>PCOD</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['PCOD'])) {
                          foreach ($result['family_history']['PCOD'] as $PCOD) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $PCOD; ?></p>
                            </div>
                        <?php }
                        }
                        ?>

                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Thyroid Dysfunction</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['ThyroidDysfunction'])) {

                          foreach ($result['family_history']['ThyroidDysfunction'] as $ThyroidDysfunction) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $ThyroidDysfunction; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Renal Dysfunction</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['RenalDysfunction'])) {
                          foreach ($result['family_history']['RenalDysfunction'] as $RenalDysfunction) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $RenalDysfunction; ?></p>
                            </div>
                        <?php }
                        }
                        ?>

                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Cardiac Problem</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['CardiacProblem'])) {
                          foreach ($result['family_history']['CardiacProblem'] as $CardiacProblem) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $CardiacProblem; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Liver Dysfunction</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['LiverDysfunction'])) {
                          foreach ($result['family_history']['LiverDysfunction'] as $LiverDysfunction) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $LiverDysfunction; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Obesity</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['Obesity'])) {

                          foreach ($result['family_history']['Obesity'] as $Obesity) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $Obesity; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Cancer</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['Cancer'])) {
                          foreach ($result['family_history']['Cancer'] as $Cancer) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $Cancer; ?></p>
                            </div>
                        <?php }
                        }
                        ?>

                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Rheumatoid Arthritis</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['RheumatoidArthritis'])) {
                          foreach ($result['family_history']['RheumatoidArthritis'] as $RheumatoidArthritis) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $RheumatoidArthritis; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Arthritis</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['Arthritis'])) {
                          foreach ($result['family_history']['Arthritis'] as $Arthritis) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $Arthritis; ?></p>
                            </div>
                        <?php }
                        }
                        ?>

                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Migraine</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['Migraine'])) {
                          foreach ($result['family_history']['Migraine'] as $Migraine) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $Migraine; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Hyperacidity</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['Hyperacidity'])) {
                          foreach ($result['family_history']['Hyperacidity'] as $Hyperacidity) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $Hyperacidity; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Chronic Constipation</h6>
                      <div class="row ">
                        <?php
                        if (!empty($result['family_history']['ChronicConstipation'])) {
                          foreach ($result['family_history']['ChronicConstipation'] as $ChronicConstipation) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $ChronicConstipation; ?></p>
                            </div>
                        <?php }
                        }
                        ?>

                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <?php
                      if (!empty($result['family_history']['AnyOther'])) {
                        foreach ($result['family_history']['AnyOther'] as $AnyOther) { ?>
                          <div class="col-md-4 col-lg-4 col-sm-4">
                            <p><?php echo $AnyOther; ?></p>
                          </div>
                      <?php }
                      }
                      ?>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h5>Physical Activity</h5>
                      <div class="row ">
                        <?php
                        if (!empty($result['physical_activity'])) {
                          foreach ($result['physical_activity'] as $physical_activity) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $physical_activity; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">How much exercise you do regularly?</label>
                      <p><?php echo $result['How_much_exercise_you_do_regularly']; ?></p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">What exercise do you prefer</label>
                      <div class="row ">
                        <?php
                        if (!empty($result['What_exercise_do_you_prefer'])) {
                          foreach ($result['What_exercise_do_you_prefer'] as $What_exercise_do_you_prefer) { ?>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <p><?php echo $What_exercise_do_you_prefer; ?></p>
                            </div>
                        <?php }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <h5>Weight History</h5>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <p><?php echo (!empty($result['Weight_History'])) ?  $result['Weight_History'] : '-'; ?></p>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">From what age did the increase of weight started?
                        <p><?php echo $result['From_what_age_did_the_increase_of_weight_started']; ?></p>
                      </label>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Which age had the maximum weight so far?
                        <p><?php echo $result['Which_age_had_the_maximum_weight_so_far']; ?></p>
                      </label>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="checkbox-label">Have you tried to lose weight in the past?</label>
                      <p><?php echo (!empty($result['Have_you_tried_to_lose_weight_in_the_past'])) ? $result['Have_you_tried_to_lose_weight_in_the_past'] : '-'; ?></p>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Mention all methods that you tried</label>
                      <p><?php echo (!empty($result['Mention_all_methods_that_you_tried'])) ? $result['Mention_all_methods_that_you_tried'] : '-'; ?></p>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Which is the main reason you are unable to lose weight?</label>
                      <p><?php echo (!empty($result['Which_is_the_main_reason_you_are_unable_to_lose_weight'])) ? $result['Which_is_the_main_reason_you_are_unable_to_lose_weight'] : '-'; ?></p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <h5>Diet History</h5>
                      <div class="row ">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <label class="checkbox-label">Are you currently on any specific diet</label>
                          <p><?php echo (!empty($result['Are_you_currently_on_any_specific_diet'])) ?: '-'; ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <label class="control-label">Yes, I am on a diet. Describe the diet</label>
                      <p><?php echo (!empty($result['Yes_I_am_on_a_diet._Describe_the_diet'])) ? $result['Yes_I_am_on_a_diet._Describe_the_diet'] : ''; ?></p>
                    </div>
                  </div>
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
                <h5 class="mt-3 mb-3">Personal Complaints</h5>
                <section id="step_3">
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <h5>General Complaints (Physical & Psychological)</h5>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <?php
                    if (!empty($result['General_Complaints'])) {
                      foreach ($result['General_Complaints'] as $General_Complaints) { ?>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <p><?php echo $General_Complaints; ?></p>
                        </div>
                    <?php }
                    }
                    ?>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <h5>Sleep Complaints</h5>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <p><?php echo (!empty($result['Sleep_Complaints'][0])) ? $result['Sleep_Complaints'][0] : '-'; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Trouble falling asleep</label>
                      <p><?php echo $result['Trouble_falling_asleep']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Trouble remaining asleep</label>
                      <p><?php echo $result['Trouble_remaining_asleep']; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Excessive sleepiness during the day</label>
                      <p><?php echo $result['Excessive_sleepiness_during_the_day']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Snoring </label>
                      <p><?php echo $result['Snoring']; ?></p>

                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Not fresh in the morning after waking up</label>
                      <p><?php echo $result['switch-one4']; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                      <h2 style="padding:0;">Sleep & Mind Health</h2>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <h5>Sleep pattern</h5>
                    </div>
                  </div>
                  <div class="form-group form-inner-line-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <p class="mb-2 color-norm">Typical bedtime:
                        <span class="color-red"><?php echo $result['Sleep_pattern']['Typical_bedtime']['weekday']; ?></span>
                        pm weekday
                        <span class="color-red"><?php echo $result['Sleep_pattern']['Typical_bedtime']['weekend']; ?></span>
                        pm weekend
                      </p>
                      <p class="mb-2 color-norm">Typical awakening time:
                        <span class="color-red"><?php echo $result['Sleep_pattern']['Typical_awakening_time']['weekday']; ?></span>
                        <input type="number" max="12" class="form-control-time timepicker" name="Sleep_pattern[Typical_awakening_time][weekday]" />
                        am weekday
                        <span class="color-red"><?php echo $result['Sleep_pattern']['Typical_awakening_time']['weekend']; ?></span>
                        am weekend
                      </p>
                      <p class="mb-2 color-norm">Typical hours in bed:
                        <span class="color-red"><?php echo $result['Sleep_pattern']['Typical_hours_in_bed']; ?></span>
                        Typical hours of sleep
                        <span class="color-red"><?php echo $result['Sleep_pattern']['Typical_hours_of_sleep']; ?></span>
                        hours
                      </p>
                      <p class="mb-2 color-norm">Typical amount of time it takes to fall asleep:
                        <span class="color-red"><?php echo $result['Sleep_pattern']['Typical_amount_of_time_it_takes_to_fall_asleep']; ?></span>
                        minutes
                      </p>
                      <p class="mb-2 color-norm">Typical number of awakenings per night
                        <span class="color-red"><?php echo $result['Sleep_pattern']['Typical_number_of_awakenings_per_night']; ?></span>
                      </p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <h5>Mind Health</h5>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <p>
                        <label class="control-label">Is your mind very anxious, scared, depressed, agitated?</label>
                      </p>
                      <div class="switch-field">
                        <span><?php echo $result['Is_your_mind_very_anxious_scared_depressed_agitated']; ?></span>
                      </div>
                      <p>
                        <label class="control-label">Reason</label>
                      </p>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <span><?php echo $result['Is_your_mind_very_anxious_scared_depressed_agitated_reason']; ?></span>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                      <h2 style="padding:0;">External Affections</h2>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <h5>Allergies</h5>
                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Are you allergic to any food?</label>
                      <p><?php echo $result['Are_you_allergic_to_any_food']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">If yes, Please explain</label>
                      <p><?php echo $result['If_yes_Please_explain']; ?></p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <h5>Addictions</h5>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <p><?php echo (!empty($result['Addictions'])) ? $result['Addictions'] : '-'; ?></p>
                    </div>
                  </div>



                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Smoking ?</label>
                      <p><?php echo $result['Smoking']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">If yes, How many</label>
                      <p><?php echo $result['Smoking_If_yes_How_many']; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Alcohol intake ?</label>
                      <p><?php echo (!empty($result['Alcohol_intake'])) ? $result['Alcohol_intake'] : '-'; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">How much quantity</label>
                      <p><?php echo $result['How_much_quantity']; ?></p>

                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Tobacco chewing ?</label>
                      <p><?php echo (!empty($result['Tobacco_chewing'])) ? $result['Tobacco_chewing'] : '-'; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">If yes, How often</label>
                      <p><?php echo $result['Tobacco_chewing_If_yes_How_often']; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                      <h2 style="padding:0;">Female Health Issues</h2>
                    </div>
                  </div>
                  <!-- <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <h5>Menstrual History</h5>
                    </div>
                  </div> -->

                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <h5>Menstrual History</h5>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <p><?php echo (!empty($result['Menstrual_History'])) ? $result['Menstrual_History'] : '-'; ?></p>
                    </div>
                  </div>

                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Irregular Menstrual cycles</label>
                      <p><?php echo (!empty($result['Irregular_Menstrual_cycles'])) ? $result['Irregular_Menstrual_cycles'] : '-'; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">How many cycles past one year</label>
                      <p><?php echo $result['How_many_cycles_past_one_year']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Dysmenorrhoea</label>
                      <p><?php echo $result['Dysmenorrhoea']; ?></p>
                    </div>
                  </div>


                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">How many days of bleeding each cycle</label>
                      <p><?php echo $result['How_many_days_of_bleeding_each_cycle']; ?></p>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">LMP</label>
                      <p><?php echo $result['LMP']; ?></p>
                    </div>
                  </div>


                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Scanty/ heavy bleeding</label>
                      <p><?php echo $result['Scanty_heavy_bleeding']; ?></p>
                    </div>
                  </div>

                  <div class="form-group form-inner-area row">

                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Menopause</label>
                      <p><?php echo $result['Menopause']; ?></p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <h5>Obstetric History</h5>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <p><?php echo (!empty($result['Obstetric_History'])) ? $result['Obstetric_History'] : '-'; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Do you have children?</label>
                      <p><?php echo $result['Do_you_have_children']; ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">No. Of Children</label>
                      <p><?php echo $result['No_Of_Children']; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Any Abortions</label>
                      <p><?php echo $result['Any_Abortions']; ?></p>
                    </div>

                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">If yes, How many Abortions? </label>
                      <p><?php echo $result['If_yes_How_many_Abortions']; ?></p>
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
                <h5 class="mt-3 mb-3">Food Habits</h5>
                <h6>Regular Habits</h6>
                <section id="step_4">
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label col-md-12 col-lg-12 col-sm-12">Wake up time&nbsp;&nbsp;
                        <p>
                          <span><?php echo $result['Wake_up_time'][0]; ?></span>
                          &nbsp;
                          <span><?php echo $result['Wake_up_time'][1]; ?></span>
                        </p>
                      </label>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Sleep time&nbsp;&nbsp;
                        <p>
                          <span><?php echo $result['Sleep_time'][0]; ?></span>
                          &nbsp;
                          <span><?php echo $result['Sleep_time'][1]; ?></span>
                        </p>
                      </label>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Departure Time (Office/school)
                        <p>
                          <span><?php echo $result['Departure_Time'][0]; ?></span>
                          &nbsp;
                          <span><?php echo $result['Departure_Time'][1]; ?></span>
                        </p>
                      </label>
                    </div>
                  </div>


                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Return/Arrival Time (From Office/school)
                        <p>
                          <span><?php echo $result['ReturnArrival_Time'][0]; ?></span>
                          &nbsp;
                          <span><?php echo $result['ReturnArrival_Time'][1]; ?></span>
                        </p>
                      </label>
                    </div>
                  </div>


                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <p><?php echo $result['foodhabit'][0]; ?></p>
                    </div>
                  </div>
                  <div class="row form-group col-md-12 col-lg-12 col-sm-12">
                    <?php
                    if (!empty($result['cuisine'])) {
                      foreach ($result['cuisine'] as $cuisine) { ?>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <p><?php echo $cuisine; ?></p>
                        </div>
                    <?php }
                    }
                    ?>

                  </div>

                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Would you like to try cuisines from other states?</label>
                      <p><?php echo $result['Would_you_like_to_try_cuisines_from_other_states']; ?></p>

                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Any preference of state</label>
                      <p><?php echo $result['Any_preference_of_state']; ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">No. Of meals per day</label>
                      <p><?php echo $result['No_Of_meals_per_day']; ?></p>

                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">How often do you eat out?
                        <p><?php echo $result['How_often_do_you_eat_out']; ?></p>
                      </label>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">Water Intake</label>
                      <p><?php echo $result['Water_Intake']; ?></p>
                    </div>

                  </div>


                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">

                      <h5>Daily dietary intake</h5>


                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="table-responeive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>MEAL /TIME</th>
                              <th>FOOD ITEMS QUANTITY </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                EARLY MORNING
                                <p>
                                  <?php echo $result['MEAL_TIME'][0] ?>
                                  &nbsp;
                                  <?php echo $result['MEAL_TIME_AM'][0] ?>
                                </p>
                              </td>
                              <td>
                                <p><?php echo $result['MEAL_QTY'][0] ?></p>
                              </td>
                            </tr>
                            <tr>
                              <td>BREAFKAST
                                <p>
                                  <?php echo $result['MEAL_TIME'][1] ?>
                                  &nbsp;
                                  <?php echo $result['MEAL_TIME_AM'][1] ?>
                                </p>
                              </td>
                              <td>
                                <p><?php echo $result['MEAL_QTY'][1] ?></p>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                MID MEAL
                                <p>
                                  <?php echo $result['MEAL_TIME'][2] ?>

                                  &nbsp;
                                  <?php echo $result['MEAL_TIME_AM'][2] ?>
                                </p>

                              </td>
                              <td>
                                <p><?php echo $result['MEAL_QTY'][2] ?></p>
                              </td>
                            </tr>
                            <tr>
                              <td>LUNCH
                                <p>
                                  <?php echo $result['MEAL_TIME'][3] ?>
                                  &nbsp;
                                  <?php echo $result['MEAL_TIME_AM'][3] ?>
                                </p>

                              </td>
                              <td>
                                <p><?php echo $result['MEAL_QTY'][3] ?></p>
                              </td>
                            </tr>
                            <tr>
                              <td>EVENING SNACK
                                <p>
                                  <?php echo $result['MEAL_TIME'][4] ?>

                                  &nbsp;
                                  <?php echo $result['MEAL_TIME_AM'][4] ?>
                                </p>
                              </td>
                              <td>
                                <p><?php echo $result['MEAL_QTY'][4] ?></p>
                              </td>
                            </tr>
                            <tr>
                              <td>DINNER
                                <p>
                                  <?php echo $result['MEAL_TIME'][5] ?>
                                  &nbsp;
                                  <?php echo $result['MEAL_TIME_AM'][5] ?>
                                </p>
                                <select name="MEAL_TIME_AM[]" style="width:60px;background-position-y: 9px !important;">
                                  <option selected>AM</option>
                                  <option>PM</option>
                                </select>
                              </td>
                              <td>
                                <p><?php echo $result['MEAL_QTY'][5] ?></p>
                              </td>
                            </tr>
                            <tr>
                              <td>BED TIME
                                <p>
                                  <?php echo $result['MEAL_TIME'][6] ?>

                                  &nbsp;
                                  <?php echo $result['MEAL_TIME_AM'][6] ?>
                                </p>
                              </td>
                              <td>
                                <p><?php echo $result['MEAL_QTY'][6] ?></p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Review of Kitchen Ingredients - Brand Name</label>
                      <div class="row">
                        <div class="col-md-6">
                          <ul>
                            <li>Salt
                              <p> <?php echo $result['Review_of_Kitchen_Ingredients_Brand_Name']['Salt'] ?></p>
                            </li>
                            <li>Milk
                              <p> <?php echo $result['Review_of_Kitchen_Ingredients_Brand_Name']['Milk'] ?></p>
                            </li>
                            <li>Ghee
                              <p> <?php echo $result['Review_of_Kitchen_Ingredients_Brand_Name']['Ghee'] ?></p>
                            </li>
                            <li>Oil
                              <p> <?php echo $result['Review_of_Kitchen_Ingredients_Brand_Name']['Oil'] ?></p>
                            </li>
                            <li>Wheat
                              <p> <?php echo $result['Review_of_Kitchen_Ingredients_Brand_Name']['Wheat'] ?></p>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Do you fast</label>
                      <p> <?php echo $result['Do_you_fast'] ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">How many days in a week
                      </label>
                      <p> <?php echo $result['How_many_days_in_a_week'] ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Preference of Food/Likes</label>
                      <p> <?php echo $result['Preference_of_FoodLikes'] ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Preference of Food/Dislikes</label>
                      <p> <?php echo $result['Preference_of_FoodDislikes'] ?></p>
                    </div>
                  </div>
                  <div class="form-group form-inner-area">
                    <p class="color-norm">TO BE FIILLED BY THE DIETICIAN</p>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">BMI</label>
                      <p> <?php echo $result['BMI'] ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">IBW</label>
                      <p> <?php echo $result['IBW'] ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Diet Goal</label>
                      <p> <?php echo $result['Diet_Goal'] ?></p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Calorie Needs</label>
                      <p> <?php echo $result['Calorie_Needs'] ?></p>
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </div>
          <div class="actions">
            <ul>
              <li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK </span></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>