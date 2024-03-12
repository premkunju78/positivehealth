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
                      <input type="date" class="form-control" name="date" />
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Full Name</label>
                      <input type="text" class="form-control" name="full_name" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Age & DOB</label>
                      <input type="date" class="form-control datepicker1" name="dob" />
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Gender</label>
                      <select class="form-control" name="gender" />
                      <option>Select</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      </select>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Occupation</label>
                      <input type="text" class="form-control" name="occupation" />
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Address</label>
                      <textarea class="form-control" name="address" rows="5"></textarea>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Phone Number 1</label>
                      <input type="text" class="form-control" name="phone_1" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Phone Number 2 (Optional)</label>
                      <input type="text" class="form-control" name="phone_2" />
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Email Address</label>
                      <input type="text" class="form-control" name="email" />
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
                      <input type="number" class="form-control" name="height" />
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                      <label class="control-label">Weight (kg)</label>
                      <input type="number" class="form-control" name="weight" />
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                      <label class="control-label">Arm (inches)</label>
                      <input type="number" class="form-control" name="arm" />
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                      <label class="control-label">Thigh (inches)</label>
                      <input type="number" class="form-control" name="thigh" />
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
                      <input type="number" class="form-control" name="neck_circumference" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Waist (inches)</label>
                      <input type="number" class="form-control" name="waist" />
                    </div>

                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Hip (inches)</label>
                      <input type="number" class="form-control" name="hip" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Waist / Hip Ratio <0.8 (Female) : <1 (Male) </label>
                          <input type="text" class="form-control" name="waist_hip_ratio" />
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
                            <tr>
                              <td><input type="text" class="form-control" name="medicine[medicine_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[medicine_dosage][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_dosage][]"></td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" name="medicine[medicine_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[medicine_dosage][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_dosage][]"></td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" name="medicine[medicine_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[medicine_dosage][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_dosage][]"></td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" name="medicine[medicine_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[medicine_dosage][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_dosage][]"></td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" name="medicine[medicine_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[medicine_dosage][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_dosage][]"></td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" name="medicine[medicine_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[medicine_dosage][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_dosage][]"></td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" name="medicine[medicine_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[medicine_dosage][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_name][]"></td>
                              <td><input type="text" class="form-control" name="medicine[supplement_dosage][]"></td>
                            </tr>
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
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="diabetes">
                          <label class="checkbox-label">Diabetes</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="hbp">
                          <label class="checkbox-label">HBP</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="hlipids">
                          <label class="checkbox-label">HLipids</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="anaemia">
                          <label class="checkbox-label">Anaemia</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="thyroid_dysfunction">
                          <label class="checkbox-label">Thyroid Dysfunction</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="renal_dysfunction">
                          <label class="checkbox-label">Renal Dysfunction</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="cardiac_problem">
                          <label class="checkbox-label">Cardiac Problem</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="pcod">
                          <label class="checkbox-label">PCOD</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="liver_dysfunction">
                          <label class="checkbox-label">Liver Dysfunction</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="obesity">
                          <label class="checkbox-label">Obesity</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="liver_dysfunction">
                          <label class="checkbox-label">Cancer</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="rheumatoid_arthritis">
                          <label class="checkbox-label">Rheumatoid Arthritis</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="arthritis">
                          <label class="checkbox-label">Arthritis</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="chronic_constipation">
                          <label class="checkbox-label">Chronic Constipation</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="migraine">
                          <label class="checkbox-label">Migraine</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="hyperacidity">
                          <label class="checkbox-label">Hyperacidity</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="anaemia">
                          <label class="checkbox-label">Anaemia</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="cheif_complaints[]" value="renal">
                          <label class="checkbox-label">Renal Stones</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="text" placeholder="Any Other..." name="cheif_complaints[]" class="form-control">
                        </div>
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
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Diabetes][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Diabetes][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Diabetes][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>HBP</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[HBP][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[HBP][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[HBP][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>H Lipid</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[HLipid][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[HLipid][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[HLipid][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Anaemia</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Anaemia][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Anaemia][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Anaemia][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>PCOD</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[PCOD][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[PCOD][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[PCOD][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Thyroid Dysfunction</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[ThyroidDysfunction][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[ThyroidDysfunction][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[ThyroidDysfunction][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Renal Dysfunction</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[RenalDysfunction][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[RenalDysfunction][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[RenalDysfunction][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Cardiac Problem</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[CardiacProblem][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[CardiacProblem][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[CardiacProblem][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Liver Dysfunction</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[LiverDysfunction][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[LiverDysfunction][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[LiverDysfunction][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Obesity</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Obesity][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Obesity][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Obesity][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Cancer</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Cancer][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Cancer][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Cancer][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Rheumatoid Arthritis</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[RheumatoidArthritis][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[RheumatoidArthritis][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[RheumatoidArthritis][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Arthritis</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Arthritis][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Arthritis][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Arthritis][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Migraine</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Migraine][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Migraine][]" value="hlipids">
                          <label class="checkbox-label">Father</label>Father
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Migraine][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Hyperacidity</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Hyperacidity][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Hyperacidity][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[Hyperacidity][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h6>Chronic Constipation</h6>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[ChronicConstipation][]" value="Mother">
                          <label class="checkbox-label">Mother</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[ChronicConstipation][]" value="Father">
                          <label class="checkbox-label">Father</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="family_history[ChronicConstipation][]" value="Others">
                          <label class="checkbox-label">Others</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <input type="text" placeholder="Any Other..." name="family_history[AnyOther][]" class="form-control">
                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <h5>Physical Activity</h5>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="physical_activity[]" value="Active">
                          <label class="checkbox-label">Active </label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="physical_activity[]" value="Moderate">
                          <label class="checkbox-label">Moderate</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="physical_activity[]" value="Sedentary">
                          <label class="checkbox-label">Sedentary</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">How much exercise you do regularly?</label>
                      <input type="text" class="form-control" name="How_much_exercise_you_do_regularly" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">What exercise do you prefer</label>
                      <div class="row ">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="What_exercise_do_you_prefer[]" value="Outdoor">
                          <label class="checkbox-label">Outdoor</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="What_exercise_do_you_prefer[]" value="Gym">
                          <label class="checkbox-label">Gym</label>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <input type="checkbox" name="What_exercise_do_you_prefer[]" value="Yoga">
                          <label class="checkbox-label">Yoga</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <h5>Weight History</h5>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="Weight_History" value="NA">
                      <label class="checkbox-label">NA</label>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">From what age did the increase of weight started?
                        <select name="From_what_age_did_the_increase_of_weight_started" style="width:100px;background-position-y: 9px !important;">
                          <option selected value=""></option>
                          <option selected></option>
                          <option>6years</option>
                          <option>7years</option>
                          <option>8years</option>
                          <option>9years</option>
                          <option>10years</option>
                          <option>11years</option>
                          <option>12years</option>
                          <option>13years</option>
                          <option>14years</option>
                          <option>15years</option>
                          <option>16years</option>
                          <option>17years</option>
                          <option>18years</option>
                          <option>19years</option>
                          <option>20years</option>
                          <option>21years</option>
                          <option>22years</option>
                          <option>23years</option>
                          <option>24years</option>
                          <option>25years</option>
                          <option>26years</option>
                          <option>27years</option>
                          <option>28years</option>
                          <option>29years</option>
                          <option>30years</option>
                          <option>31years</option>
                          <option>32years</option>
                          <option>33years</option>
                          <option>34years</option>
                          <option>35years</option>
                          <option>36years</option>
                          <option>37years</option>
                          <option>38years</option>
                          <option>39years</option>
                          <option>40years</option>
                          <option>41years</option>
                          <option>42years</option>
                          <option>43years</option>
                          <option>44years</option>
                          <option>45years</option>
                          <option>46years</option>
                          <option>47years</option>
                          <option>48years</option>
                          <option>49years</option>
                          <option>50years</option>
                          <option>41years</option>
                          <option>42years</option>
                          <option>43years</option>
                          <option>44years</option>
                          <option>45years</option>
                          <option>46years</option>
                          <option>47years</option>
                          <option>48years</option>
                          <option>49years</option>
                          <option>50years</option>
                          <option>51years</option>
                          <option>52years</option>
                          <option>53years</option>
                          <option>54years</option>
                          <option>55years</option>
                          <option>56years</option>
                          <option>57years</option>
                          <option>58years</option>
                          <option>59years</option>
                          <option>60years</option>
                          <option>61years</option>
                          <option>62years</option>
                          <option>63years</option>
                          <option>64years</option>
                          <option>65years</option>
                          <option>66years</option>
                          <option>67years</option>
                          <option>68years</option>
                          <option>69years</option>
                          <option>70years</option>
                          <option>71years</option>
                          <option>72years</option>
                          <option>73years</option>
                          <option>74years</option>
                          <option>75years</option>
                          <option>76years</option>
                          <option>77years</option>
                          <option>78years</option>
                          <option>79years</option>
                          <option>80years</option>
                        </select>
                      </label>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Which age had the maximum weight so far?
                        <select name="Which_age_had_the_maximum_weight_so_far" style="width:100px;background-position-y: 9px !important;">
                          <option selected value=""></option>
                          <option selected>5years</option>
                          <option>6years</option>
                          <option>7years</option>
                          <option>8years</option>
                          <option>9years</option>
                          <option>10years</option>
                          <option>11years</option>
                          <option>12years</option>
                          <option>13years</option>
                          <option>14years</option>
                          <option>15years</option>
                          <option>16years</option>
                          <option>17years</option>
                          <option>18years</option>
                          <option>19years</option>
                          <option>20years</option>
                          <option>21years</option>
                          <option>22years</option>
                          <option>23years</option>
                          <option>24years</option>
                          <option>25years</option>
                          <option>26years</option>
                          <option>27years</option>
                          <option>28years</option>
                          <option>29years</option>
                          <option>30years</option>
                          <option>31years</option>
                          <option>32years</option>
                          <option>33years</option>
                          <option>34years</option>
                          <option>35years</option>
                          <option>36years</option>
                          <option>37years</option>
                          <option>38years</option>
                          <option>39years</option>
                          <option>40years</option>
                          <option>41years</option>
                          <option>42years</option>
                          <option>43years</option>
                          <option>44years</option>
                          <option>45years</option>
                          <option>46years</option>
                          <option>47years</option>
                          <option>48years</option>
                          <option>49years</option>
                          <option>50years</option>
                          <option>41years</option>
                          <option>42years</option>
                          <option>43years</option>
                          <option>44years</option>
                          <option>45years</option>
                          <option>46years</option>
                          <option>47years</option>
                          <option>48years</option>
                          <option>49years</option>
                          <option>50years</option>
                          <option>51years</option>
                          <option>52years</option>
                          <option>53years</option>
                          <option>54years</option>
                          <option>55years</option>
                          <option>56years</option>
                          <option>57years</option>
                          <option>58years</option>
                          <option>59years</option>
                          <option>60years</option>
                          <option>61years</option>
                          <option>62years</option>
                          <option>63years</option>
                          <option>64years</option>
                          <option>65years</option>
                          <option>66years</option>
                          <option>67years</option>
                          <option>68years</option>
                          <option>69years</option>
                          <option>70years</option>
                          <option>71years</option>
                          <option>72years</option>
                          <option>73years</option>
                          <option>74years</option>
                          <option>75years</option>
                          <option>76years</option>
                          <option>77years</option>
                          <option>78years</option>
                          <option>79years</option>
                          <option>80years</option>
                        </select>
                      </label>

                    </div>
                  </div>
                  <div class="form-group  row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="checkbox-label">Have you tried to lose weight in the past?</label>
                      <input type="radio" name="Have_you_tried_to_lose_weight_in_the_past" value="Yes">
                      <label class="checkbox-label">Yes</label>
                      <input type="radio" name="Have_you_tried_to_lose_weight_in_the_past" value="No">
                      <label class="checkbox-label">No</label>
                    </div>
                  </div>
                  <div class="form-group row form-inner-area">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Mention all methods that you tried</label>
                      <input type="text" class="form-control" name="Mention_all_methods_that_you_tried" />
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Which is the main reason you are unable to lose weight?</label>
                      <input type="text" class="form-control" name="Which_is_the_main_reason_you_are_unable_to_lose_weight" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <h5>Diet History</h5>
                      <div class="row ">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <label class="checkbox-label">Are you currently on any specific diet</label>
                          <input type="radio" name="Are_you_currently_on_any_specific_diet" value="Yes">
                          <label class="checkbox-label">Yes</label>
                          <input type="radio" name="Are_you_currently_on_any_specific_diet" value="No">
                          <label class="checkbox-label">No</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <label class="control-label">Yes, I am on a diet. Describe the diet</label>
                      <input type="text" class="form-control" name="Yes_I_am_on_a_diet._Describe_the_diet" />
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
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Headache/Migraine">
                      <label class="checkbox-label">Headache/Migraine</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Dizziness">
                      <label class="checkbox-label">Dizziness</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Neck pain">
                      <label class="checkbox-label">Neck pain</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Snoring">
                      <label class="checkbox-label">Snoring</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Breathing Problem">
                      <label class="checkbox-label">Breathing Problem</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Chest Pain">
                      <label class="checkbox-label">Chest Pain</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Acidity">
                      <label class="checkbox-label">Acidity</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Gastric Disturbance">
                      <label class="checkbox-label">Gastric Disturbance</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Stomach Pain">
                      <label class="checkbox-label">Stomach Pain</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Indigestion">
                      <label class="checkbox-label">Indigestion</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Flatulence">
                      <label class="checkbox-label">Flatulence</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Back Pain">
                      <label class="checkbox-label">Back Pain</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Joint Pain">
                      <label class="checkbox-label">Joint Pain</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Anxiety">
                      <label class="checkbox-label">Anxiety</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Depression">
                      <label class="checkbox-label">Depression</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Fatigue">
                      <label class="checkbox-label">Fatigue</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="General_Complaints[]" value="Insomnia/over sleeping">
                      <label class="checkbox-label">Insomnia/over sleeping</label>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <input type="checkbox" name="General_Complaints[]" value="Loss of Appetite/Excessive Appetite">
                      <label class="checkbox-label">Loss of Appetite/Excessive Appetite</label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <h5>Sleep Complaints</h5>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="Sleep_Complaints[]" value="NA">
                      <label class="checkbox-label">NA</label>
                    </div>

                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Trouble falling asleep</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one7" name="Trouble_falling_asleep" value="Yes" checked />
                        <label for="radio-one7">Yes</label>
                        <input type="radio" id="radio-two7" name="Trouble_falling_asleep" value="No" />
                        <label for="radio-two7">No</label>
                      </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Trouble remaining asleep</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one1" name="Trouble_remaining_asleep" value="Yes" checked />
                        <label for="radio-one1">Yes</label>
                        <input type="radio" id="radio-two1" name="Trouble_remaining_asleep" value="No" />
                        <label for="radio-two1">No</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Excessive sleepiness during the day</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one2" name="Excessive_sleepiness_during_the_day" value="Yes" checked />
                        <label for="radio-one2">Yes</label>
                        <input type="radio" id="radio-two2" name="Excessive_sleepiness_during_the_day" value="No" />
                        <label for="radio-two2">No</label>
                      </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Snoring </label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one3" name="Snoring" value="Yes" checked />
                        <label for="radio-one3">Yes</label>
                        <input type="radio" id="radio-two3" name="Snoring" value="No" />
                        <label for="radio-two3">No</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Not fresh in the morning after waking up</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one4" name="switch-one4" value="yes" checked />
                        <label for="radio-one4">Yes</label>
                        <input type="radio" id="radio-two4" name="switch-one4" value="no" />
                        <label for="radio-two4">No</label>
                      </div>
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
                      <p class="mb-2">Typical bedtime:
                        <input type="number" max="12" class="form-control-time timepicker" name="Sleep_pattern[Typical_bedtime][weekday]" />
                        pm weekday
                        <input type="number" max="12" class="form-control-time timepicker" name="Sleep_pattern[Typical_bedtime][weekend]" />
                        pm weekend
                      </p>
                      <p class="mb-2">Typical awakening time:
                        <input type="number" max="12" class="form-control-time timepicker" name="Sleep_pattern[Typical_awakening_time][weekday]" />
                        am weekday
                        <input type="number" max="12" class="form-control-time timepicker" name="Sleep_pattern[Typical_awakening_time][weekend]" />
                        am weekend
                      </p>
                      <p class="mb-2">Typical hours in bed:
                        <input type="number" max="12" class="form-control-time timepicker" name="Sleep_pattern[Typical_hours_in_bed]" />
                        Typical hours of sleep
                        <input type="number" max="12" class="form-control-time timepicker" name="Sleep_pattern[Typical_hours_of_sleep]" />
                        hours
                      </p>
                      <p class="mb-2">Typical amount of time it takes to fall asleep:
                        <input type="number" max="12" class="form-control-time timepicker" name="Sleep_pattern[Typical_amount_of_time_it_takes_to_fall_asleep]" />
                        minutes
                      </p>
                      <p class="mb-2">Typical number of awakenings per night
                        <input type="number" max="12" class="form-control-time timepicker" name="Sleep_pattern[Typical_number_of_awakenings_per_night]" />
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
                        <input type="radio" id="radio-one5" name="Is_your_mind_very_anxious_scared_depressed_agitated" value="yes" checked />
                        <label for="radio-one5">Yes</label>
                        <input type="radio" id="radio-two5" name="Is_your_mind_very_anxious_scared_depressed_agitated" value="no" />
                        <label for="radio-two5">No</label>
                      </div>
                      <p>
                        <label class="control-label">Reason</label>
                      </p>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <textarea class="form-control" rows="4" name="Is_your_mind_very_anxious_scared_depressed_agitated_reason" />
                      </textarea>
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

                      <div class="switch-field">
                        <input type="radio" id="radio-one" name="Are_you_allergic_to_any_food" value="yes" checked />
                        <label for="radio-one">Yes</label>
                        <input type="radio" id="radio-two" name="Are_you_allergic_to_any_food" value="no" />
                        <label for="radio-two">No</label>
                      </div>

                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">If yes, Please explain</label>
                      <textarea class="form-control" name="If_yes_Please_explain" rows="3"></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <h5>Addictions</h5>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="Addictions" value="NA">
                      <label class="checkbox-label">NA</label>
                    </div>
                  </div>



                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Smoking ?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one10" name="Smoking" value="yes" checked />
                        <label for="radio-one10">Yes</label>
                        <input type="radio" id="radio-two10" name="Smoking" value="no" />
                        <label for="radio-two10">No</label>
                      </div>

                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">If yes, How many</label>
                      <input type="text" class="form-control" name="Smoking_If_yes_How_many">
                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Alcohol intake ?</label>
                      <input type="radio" class="form-control-radio" value="1" name="Alcohol_intake" />
                      Yes
                      <input type="radio" class="form-control-radio" value="0" name="Alcohol_intake" />
                      No
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">How much quantity</label>
                      <input type="number" class="form-control" name="How_much_quantity">
                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Tobacco chewing ?</label>
                      <input type="radio" class="form-control-radio" value="1" name="Tobacco_chewing" />
                      Yes
                      <input type="radio" class="form-control-radio" value="0" name="Tobacco_chewing" />
                      No
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">If yes, How often</label>
                      <input type="number" class="form-control" name="Tobacco_chewing_If_yes_How_often">
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
                      <input type="checkbox" name="Menstrual_History" value="NA">
                      <label class="checkbox-label">NA</label>
                    </div>
                  </div>

                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Irregular Menstrual cycles</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one11" name="Irregular_Menstrual_cycles" value="yes" checked />
                        <label for="radio-one11">Yes</label>
                        <input type="radio" id="radio-two11" name="Irregular_Menstrual_cycles" value="no" />
                        <label for="radio-two11">No</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">How many cycles past one year</label>
                      <input type="number" class="form-control" name="How_many_cycles_past_one_year" style="width:80px" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Dysmenorrhoea</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one12" name="Dysmenorrhoea" value="yes" checked />
                        <label for="radio-one12">Yes</label>
                        <input type="radio" id="radio-two12" name="Dysmenorrhoea" value="no" />
                        <label for="radio-two12">No</label>
                      </div>
                    </div>
                  </div>


                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">How many days of bleeding each cycle</label>
                      <input type="number" class="form-control" name="How_many_days_of_bleeding_each_cycle" style="width:80px" />
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">LMP</label>
                      <input type="date" class="form-control" name="LMP" />
                    </div>
                  </div>


                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Scanty/ heavy bleeding</label>
                      <input type="text" class="form-control" name="Scanty_heavy_bleeding" />
                    </div>
                  </div>

                  <div class="form-group form-inner-area row">

                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Menopause</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one13" name="Menopause" value="yes" checked />
                        <label for="radio-one13">Yes</label>
                        <input type="radio" id="radio-two13" name="Menopause" value="no" />
                        <label for="radio-two13">No</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <h5>Obstetric History</h5>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <input type="checkbox" name="Obstetric_History" value="NA">
                      <label class="checkbox-label">NA</label>
                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Do you have children?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one14" name="Do_you_have_children" value="yes" checked />
                        <label for="radio-one14">Yes</label>
                        <input type="radio" id="radio-two14" name="Do_you_have_children" value="no" />
                        <label for="radio-two14">No</label>
                      </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">No. Of Children</label>
                      <input type="number" class="form-control" name="No_Of_Children" />
                    </div>
                  </div>
                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Any Abortions</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one15" name="Any_Abortions" value="yes" checked />
                        <label for="radio-one15">Yes</label>
                        <input type="radio" id="radio-two15" name="Any_Abortions" value="no" />
                        <label for="radio-two15">No</label>
                      </div>
                    </div>

                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">If yes, How many Abortions? </label>
                      <input type="number" class="form-control" name="If_yes_How_many_Abortions" />
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
                        <select name="Wake_up_time[]" style="width:100px;background-position-y: 9px !important;">
                          <option selected>04:00</option>
                          <option>04:15</option>
                          <option>04:30</option>
                          <option>04:45</option>
                          <option>05:00</option>
                          <option>05:15</option>
                          <option>05:30</option>
                          <option>5:45</option>
                          <option>06:00</option>
                          <option>06:15</option>
                          <option>06:30</option>
                          <option>06:45</option>
                          <option>07:00</option>
                          <option>07:15</option>
                          <option>07:30</option>
                          <option>07:45</option>
                          <option>08:00</option>
                          <option>08:15</option>
                          <option>08:30</option>
                          <option>08:45</option>
                          <option>09:00</option>
                          <option>09:15</option>
                          <option>09:30</option>
                          <option>09:45</option>
                          <option>10:00</option>
                          <option>10:15</option>
                          <option>10:30</option>
                          <option>10:45</option>
                          <option>11:00</option>
                        </select>
                        &nbsp;
                        <select name="Wake_up_time[]" style="width:60px;background-position-y: 9px !important;">
                          <option selected>AM</option>
                          <option>PM</option>
                        </select></label>

                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Sleep time&nbsp;&nbsp;
                        <select name="Sleep_time[]" style="width:100px;background-position-y: 9px !important;">
                          <option selected>08:00</option>
                          <option>08:15</option>
                          <option>08:30</option>
                          <option>08:45</option>
                          <option>09:00</option>
                          <option>09:15</option>
                          <option>09:30</option>
                          <option>09:45</option>
                          <option>10:00</option>
                          <option>10:15</option>
                          <option>10:30</option>
                          <option>10:45</option>
                          <option>11:00</option>
                          <option>11:15</option>
                          <option>11:30</option>
                          <option>11:45</option>
                          <option>12:00</option>
                          <option>12:15</option>
                          <option>12:30</option>
                          <option>12:45</option>
                          <option>01:00</option>
                          <option>01:15</option>
                          <option>01:30</option>
                          <option>01:45</option>
                          <option>02:00</option>
                          <option>02:15</option>
                          <option>02:30</option>
                          <option>02:45</option>
                        </select>
                        &nbsp;
                        <select name="Sleep_time[]" style="width:60px;background-position-y: 9px !important;">
                          <option selected>AM</option>
                          <option>PM</option>
                        </select></label>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Departure Time (Office/school)
                        <select name="Departure_Time[]" style="width:100px;background-position-y: 9px !important;">
                          <option>01:00</option>
                          <option>01:15</option>
                          <option>01:30</option>
                          <option>01:45</option>
                          <option>02:00</option>
                          <option>02:15</option>
                          <option>02:30</option>
                          <option>02:45</option>
                          <option>03:00</option>
                          <option>03:15</option>
                          <option>03:30</option>
                          <option>03:45</option>
                          <option>04:00</option>
                          <option>04:15</option>
                          <option>04:30</option>
                          <option>04:45</option>
                          <option>05:00</option>
                          <option>05:15</option>
                          <option>05:30</option>
                          <option>05:45</option>
                          <option>06:00</option>
                          <option>06:15</option>
                          <option>06:30</option>
                          <option>06:45</option>
                          <option>07:00</option>
                          <option>07:15</option>
                          <option>07:30</option>
                          <option>07:45</option>
                          <option>08:00</option>
                          <option>08:15</option>
                          <option>08:30</option>
                          <option>08:45</option>
                          <option>09:00</option>
                          <option>09:15</option>
                          <option>09:30</option>
                          <option>09:45</option>
                          <option>10:00</option>
                          <option>10:15</option>
                          <option>10:30</option>
                          <option>10:45</option>
                          <option>11:00</option>
                          <option>11:15</option>
                          <option>11:30</option>
                          <option>11:45</option>
                          <option>12:00</option>
                          <option>12:15</option>
                          <option>12:30</option>
                          <option>12:45</option>
                        </select>
                        &nbsp;
                        <select name="Departure_Time[]" style="width:60px;background-position-y: 9px !important;">
                          <option selected>AM</option>
                          <option>PM</option>
                        </select></label>
                    </div>
                  </div>


                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Return/Arrival Time (From Office/school)
                        <select name="ReturnArrival_Time[]" style="width:100px;background-position-y: 9px !important;">
                          <option>01:00</option>
                          <option>01:15</option>
                          <option>01:30</option>
                          <option>01:45</option>
                          <option>02:00</option>
                          <option>02:15</option>
                          <option>02:30</option>
                          <option>02:45</option>
                          <option>03:00</option>
                          <option>03:15</option>
                          <option>03:30</option>
                          <option>03:45</option>
                          <option>04:00</option>
                          <option>04:15</option>
                          <option>04:30</option>
                          <option>04:45</option>
                          <option>05:00</option>
                          <option>05:15</option>
                          <option>05:30</option>
                          <option>05:45</option>
                          <option>06:00</option>
                          <option>06:15</option>
                          <option>06:30</option>
                          <option>06:45</option>
                          <option>07:00</option>
                          <option>07:15</option>
                          <option>07:30</option>
                          <option>07:45</option>
                          <option>08:00</option>
                          <option>08:15</option>
                          <option>08:30</option>
                          <option>08:45</option>
                          <option>09:00</option>
                          <option>09:15</option>
                          <option>09:30</option>
                          <option>09:45</option>
                          <option>10:00</option>
                          <option>10:15</option>
                          <option>10:30</option>
                          <option>10:45</option>
                          <option>11:00</option>
                          <option>11:15</option>
                          <option>11:30</option>
                          <option>11:45</option>
                          <option>12:00</option>
                          <option>12:15</option>
                          <option>12:30</option>
                          <option>12:45</option>
                        </select>
                        &nbsp;
                        <select name="ReturnArrival_Time[]" style="width:60px;background-position-y: 9px !important;">
                          <option selected>AM</option>
                          <option>PM</option>
                        </select></label>
                    </div>
                  </div>


                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">

                      <div class="switch-field">

                        <input type="radio" id="radio-three" name="foodhabit[]" value="Veg" checked />
                        <label for="radio-three">Veg</label>

                        <input type="radio" id="radio-four" name="foodhabit[]" value="Non-Veg" />
                        <label for="radio-four">Non-Veg</label>

                        <input type="radio" id="radio-five" name="foodhabit[]" value="Vegan" />
                        <label for="radio-five">Vegan</label>

                        <input type="radio" id="radio-six" name="foodhabit[]" value="Jain" />
                        <label for="radio-six">Jain</label>

                        <input type="radio" id="radio-seven" name="foodhabit[]" value="egg" />
                        <label for="radio-seven">Egg only</label>



                      </div>
                    </div>
                  </div>
                  <div class="row form-group col-md-12 col-lg-12 col-sm-12">
                    <div class="col-md-3 col-lg-3 col-sm-3">
                      <input type="checkbox" name="cuisine[]" value="South Indian">
                      <label class="checkbox-label">South Indian</label>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-3">
                      <input type="checkbox" name="cuisine[]" value="North Indian">
                      <label class="checkbox-label">North Indian</label>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-3">
                      <input type="checkbox" name="cuisine[]" value="East Indian">
                      <label class="checkbox-label">East Indian</label>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-3">
                      <input type="checkbox" name="cuisine[]" value="West India">
                      <label class="checkbox-label">West India</label>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-3">
                      <input type="checkbox" name="cuisine[]" value="Continental">
                      <label class="checkbox-label">Continental</label>
                    </div>
                  </div>

                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Would you like to try cuisines from other states?</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one100" name="Would_you_like_to_try_cuisines_from_other_states" value="yes" checked />
                        <label for="radio-one100">Yes</label>
                        <input type="radio" id="radio-two100" name="Would_you_like_to_try_cuisines_from_other_states" value="no" />
                        <label for="radio-two100">No</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Any preference of state</label>
                      <input type="text" class="form-control timepicker" name="Any_preference_of_state" />
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">No. Of meals per day</label>
                      <select name="No_Of_meals_per_day" style="width:100px;background-position-y: 9px !important;">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                        <option>13</option>
                        <option>14</option>
                        <option>15</option>
                      </select>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">How often do you eat out?
                        <select name="How_often_do_you_eat_out" style="width:100px;background-position-y: 9px !important;">
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                          <option>6</option>
                          <option>7</option>
                          <option>8</option>
                          <option>9</option>
                          <option>10</option>
                          <option>11</option>
                          <option>12</option>
                          <option>13</option>
                          <option>14</option>
                          <option>15</option>
                        </select></label>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label class="control-label">Water Intake</label>
                      <input type="text" class="form-control timepicker" name="Water_Intake" />
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
                              <td>EARLY MORNING
                                <select name="MEAL_TIME[]" style="width:100px;background-position-y: 9px !important;">
                                  <option>01:00</option>
                                  <option>01:15</option>
                                  <option>01:30</option>
                                  <option>01:45</option>
                                  <option>02:00</option>
                                  <option>02:15</option>
                                  <option>02:30</option>
                                  <option>02:45</option>
                                  <option>03:00</option>
                                  <option>03:15</option>
                                  <option>03:30</option>
                                  <option>03:45</option>
                                  <option>04:00</option>
                                  <option>04:15</option>
                                  <option>04:30</option>
                                  <option>04:45</option>
                                  <option>05:00</option>
                                  <option>05:15</option>
                                  <option>05:30</option>
                                  <option>05:45</option>
                                  <option>06:00</option>
                                  <option>06:15</option>
                                  <option>06:30</option>
                                  <option>06:45</option>
                                  <option>07:00</option>
                                  <option>07:15</option>
                                  <option>07:30</option>
                                  <option>07:45</option>
                                  <option>08:00</option>
                                  <option>08:15</option>
                                  <option>08:30</option>
                                  <option>08:45</option>
                                  <option>09:00</option>
                                  <option>09:15</option>
                                  <option>09:30</option>
                                  <option>09:45</option>
                                  <option>10:00</option>
                                  <option>10:15</option>
                                  <option>10:30</option>
                                  <option>10:45</option>
                                  <option>11:00</option>
                                  <option>11:15</option>
                                  <option>11:30</option>
                                  <option>11:45</option>
                                  <option>12:00</option>
                                  <option>12:15</option>
                                  <option>12:30</option>
                                  <option>12:45</option>
                                </select>
                                &nbsp;
                                <select name="MEAL_TIME_AM[]" style="width:60px;background-position-y: 9px !important;">
                                  <option selected>AM</option>
                                  <option>PM</option>
                                </select>
                              </td>
                              <td><input type="text" max="24" name="MEAL_QTY[]" class="form-control"></td>
                            </tr>
                            <tr>
                              <td>BREAFKAST <select name="MEAL_TIME[]" style="width:100px;background-position-y: 9px !important;">
                                  <option>01:00</option>
                                  <option>01:15</option>
                                  <option>01:30</option>
                                  <option>01:45</option>
                                  <option>02:00</option>
                                  <option>02:15</option>
                                  <option>02:30</option>
                                  <option>02:45</option>
                                  <option>03:00</option>
                                  <option>03:15</option>
                                  <option>03:30</option>
                                  <option>03:45</option>
                                  <option>04:00</option>
                                  <option>04:15</option>
                                  <option>04:30</option>
                                  <option>04:45</option>
                                  <option>05:00</option>
                                  <option>05:15</option>
                                  <option>05:30</option>
                                  <option>05:45</option>
                                  <option>06:00</option>
                                  <option>06:15</option>
                                  <option>06:30</option>
                                  <option>06:45</option>
                                  <option>07:00</option>
                                  <option>07:15</option>
                                  <option>07:30</option>
                                  <option>07:45</option>
                                  <option>08:00</option>
                                  <option>08:15</option>
                                  <option>08:30</option>
                                  <option>08:45</option>
                                  <option>09:00</option>
                                  <option>09:15</option>
                                  <option>09:30</option>
                                  <option>09:45</option>
                                  <option>10:00</option>
                                  <option>10:15</option>
                                  <option>10:30</option>
                                  <option>10:45</option>
                                  <option>11:00</option>
                                  <option>11:15</option>
                                  <option>11:30</option>
                                  <option>11:45</option>
                                  <option>12:00</option>
                                  <option>12:15</option>
                                  <option>12:30</option>
                                  <option>12:45</option>
                                </select>
                                &nbsp;
                                <select name="MEAL_TIME_AM[]" style="width:60px;background-position-y: 9px !important;">
                                  <option selected>AM</option>
                                  <option>PM</option>
                                </select>
                              </td>
                              <td><input type="text" max="12" name="MEAL_QTY[]" class="form-control"></td>
                            </tr>
                            <tr>
                              <td>MID MEAL <select name="MEAL_TIME[]" style="width:100px;background-position-y: 9px !important;">
                                  <option>01:00</option>
                                  <option>01:15</option>
                                  <option>01:30</option>
                                  <option>01:45</option>
                                  <option>02:00</option>
                                  <option>02:15</option>
                                  <option>02:30</option>
                                  <option>02:45</option>
                                  <option>03:00</option>
                                  <option>03:15</option>
                                  <option>03:30</option>
                                  <option>03:45</option>
                                  <option>04:00</option>
                                  <option>04:15</option>
                                  <option>04:30</option>
                                  <option>04:45</option>
                                  <option>05:00</option>
                                  <option>05:15</option>
                                  <option>05:30</option>
                                  <option>05:45</option>
                                  <option>06:00</option>
                                  <option>06:15</option>
                                  <option>06:30</option>
                                  <option>06:45</option>
                                  <option>07:00</option>
                                  <option>07:15</option>
                                  <option>07:30</option>
                                  <option>07:45</option>
                                  <option>08:00</option>
                                  <option>08:15</option>
                                  <option>08:30</option>
                                  <option>08:45</option>
                                  <option>09:00</option>
                                  <option>09:15</option>
                                  <option>09:30</option>
                                  <option>09:45</option>
                                  <option>10:00</option>
                                  <option>10:15</option>
                                  <option>10:30</option>
                                  <option>10:45</option>
                                  <option>11:00</option>
                                  <option>11:15</option>
                                  <option>11:30</option>
                                  <option>11:45</option>
                                  <option>12:00</option>
                                  <option>12:15</option>
                                  <option>12:30</option>
                                  <option>12:45</option>
                                </select>
                                &nbsp;
                                <select name="MEAL_TIME_AM[]" style="width:60px;background-position-y: 9px !important;">
                                  <option selected>AM</option>
                                  <option>PM</option>
                                </select>
                              </td>
                              <td><input type="text" max="12" name="MEAL_QTY[]" class="form-control"></td>
                            </tr>
                            <tr>
                              <td>LUNCH <select name="MEAL_TIME[]" style="width:100px;background-position-y: 9px !important;">
                                  <option>01:00</option>
                                  <option>01:15</option>
                                  <option>01:30</option>
                                  <option>01:45</option>
                                  <option>02:00</option>
                                  <option>02:15</option>
                                  <option>02:30</option>
                                  <option>02:45</option>
                                  <option>03:00</option>
                                  <option>03:15</option>
                                  <option>03:30</option>
                                  <option>03:45</option>
                                  <option>04:00</option>
                                  <option>04:15</option>
                                  <option>04:30</option>
                                  <option>04:45</option>
                                  <option>05:00</option>
                                  <option>05:15</option>
                                  <option>05:30</option>
                                  <option>05:45</option>
                                  <option>06:00</option>
                                  <option>06:15</option>
                                  <option>06:30</option>
                                  <option>06:45</option>
                                  <option>07:00</option>
                                  <option>07:15</option>
                                  <option>07:30</option>
                                  <option>07:45</option>
                                  <option>08:00</option>
                                  <option>08:15</option>
                                  <option>08:30</option>
                                  <option>08:45</option>
                                  <option>09:00</option>
                                  <option>09:15</option>
                                  <option>09:30</option>
                                  <option>09:45</option>
                                  <option>10:00</option>
                                  <option>10:15</option>
                                  <option>10:30</option>
                                  <option>10:45</option>
                                  <option>11:00</option>
                                  <option>11:15</option>
                                  <option>11:30</option>
                                  <option>11:45</option>
                                  <option>12:00</option>
                                  <option>12:15</option>
                                  <option>12:30</option>
                                  <option>12:45</option>
                                </select>
                                &nbsp;
                                <select name="MEAL_TIME_AM[]" style="width:60px;background-position-y: 9px !important;">
                                  <option selected>AM</option>
                                  <option>PM</option>
                                </select>
                              </td>
                              <td><input type="text" name="MEAL_QTY[]" max="12" class="form-control"></td>
                            </tr>
                            <tr>
                              <td>EVENING SNACK <select name="MEAL_TIME[]" style="width:100px;background-position-y: 9px !important;">
                                  <option>01:00</option>
                                  <option>01:15</option>
                                  <option>01:30</option>
                                  <option>01:45</option>
                                  <option>02:00</option>
                                  <option>02:15</option>
                                  <option>02:30</option>
                                  <option>02:45</option>
                                  <option>03:00</option>
                                  <option>03:15</option>
                                  <option>03:30</option>
                                  <option>03:45</option>
                                  <option>04:00</option>
                                  <option>04:15</option>
                                  <option>04:30</option>
                                  <option>04:45</option>
                                  <option>05:00</option>
                                  <option>05:15</option>
                                  <option>05:30</option>
                                  <option>05:45</option>
                                  <option>06:00</option>
                                  <option>06:15</option>
                                  <option>06:30</option>
                                  <option>06:45</option>
                                  <option>07:00</option>
                                  <option>07:15</option>
                                  <option>07:30</option>
                                  <option>07:45</option>
                                  <option>08:00</option>
                                  <option>08:15</option>
                                  <option>08:30</option>
                                  <option>08:45</option>
                                  <option>09:00</option>
                                  <option>09:15</option>
                                  <option>09:30</option>
                                  <option>09:45</option>
                                  <option>10:00</option>
                                  <option>10:15</option>
                                  <option>10:30</option>
                                  <option>10:45</option>
                                  <option>11:00</option>
                                  <option>11:15</option>
                                  <option>11:30</option>
                                  <option>11:45</option>
                                  <option>12:00</option>
                                  <option>12:15</option>
                                  <option>12:30</option>
                                  <option>12:45</option>
                                </select>
                                &nbsp;
                                <select name="MEAL_TIME_AM[]" style="width:60px;background-position-y: 9px !important;">
                                  <option selected>AM</option>
                                  <option>PM</option>
                                </select>
                              </td>
                              <td><input type="text" name="MEAL_QTY[]" max="12" class="form-control"></td>
                            </tr>
                            <tr>
                              <td>DINNER <select name="MEAL_TIME[]" style="width:100px;background-position-y: 9px !important;">
                                  <option>01:00</option>
                                  <option>01:15</option>
                                  <option>01:30</option>
                                  <option>01:45</option>
                                  <option>02:00</option>
                                  <option>02:15</option>
                                  <option>02:30</option>
                                  <option>02:45</option>
                                  <option>03:00</option>
                                  <option>03:15</option>
                                  <option>03:30</option>
                                  <option>03:45</option>
                                  <option>04:00</option>
                                  <option>04:15</option>
                                  <option>04:30</option>
                                  <option>04:45</option>
                                  <option>05:00</option>
                                  <option>05:15</option>
                                  <option>05:30</option>
                                  <option>05:45</option>
                                  <option>06:00</option>
                                  <option>06:15</option>
                                  <option>06:30</option>
                                  <option>06:45</option>
                                  <option>07:00</option>
                                  <option>07:15</option>
                                  <option>07:30</option>
                                  <option>07:45</option>
                                  <option>08:00</option>
                                  <option>08:15</option>
                                  <option>08:30</option>
                                  <option>08:45</option>
                                  <option>09:00</option>
                                  <option>09:15</option>
                                  <option>09:30</option>
                                  <option>09:45</option>
                                  <option>10:00</option>
                                  <option>10:15</option>
                                  <option>10:30</option>
                                  <option>10:45</option>
                                  <option>11:00</option>
                                  <option>11:15</option>
                                  <option>11:30</option>
                                  <option>11:45</option>
                                  <option>12:00</option>
                                  <option>12:15</option>
                                  <option>12:30</option>
                                  <option>12:45</option>
                                </select>
                                &nbsp;
                                <select name="MEAL_TIME_AM[]" style="width:60px;background-position-y: 9px !important;">
                                  <option selected>AM</option>
                                  <option>PM</option>
                                </select>
                              </td>
                              <td><input type="text" name="MEAL_QTY[]" max="12" class="form-control"></td>
                            </tr>
                            <tr>
                              <td>BED TIME <select name="MEAL_TIME[]" style="width:100px;background-position-y: 9px !important;">
                                  <option>01:00</option>
                                  <option>01:15</option>
                                  <option>01:30</option>
                                  <option>01:45</option>
                                  <option>02:00</option>
                                  <option>02:15</option>
                                  <option>02:30</option>
                                  <option>02:45</option>
                                  <option>03:00</option>
                                  <option>03:15</option>
                                  <option>03:30</option>
                                  <option>03:45</option>
                                  <option>04:00</option>
                                  <option>04:15</option>
                                  <option>04:30</option>
                                  <option>04:45</option>
                                  <option>05:00</option>
                                  <option>05:15</option>
                                  <option>05:30</option>
                                  <option>05:45</option>
                                  <option>06:00</option>
                                  <option>06:15</option>
                                  <option>06:30</option>
                                  <option>06:45</option>
                                  <option>07:00</option>
                                  <option>07:15</option>
                                  <option>07:30</option>
                                  <option>07:45</option>
                                  <option>08:00</option>
                                  <option>08:15</option>
                                  <option>08:30</option>
                                  <option>08:45</option>
                                  <option>09:00</option>
                                  <option>09:15</option>
                                  <option>09:30</option>
                                  <option>09:45</option>
                                  <option>10:00</option>
                                  <option>10:15</option>
                                  <option>10:30</option>
                                  <option>10:45</option>
                                  <option>11:00</option>
                                  <option>11:15</option>
                                  <option>11:30</option>
                                  <option>11:45</option>
                                  <option>12:00</option>
                                  <option>12:15</option>
                                  <option>12:30</option>
                                  <option>12:45</option>
                                </select>
                                &nbsp;
                                <select name="MEAL_TIME_AM[]" style="width:60px;background-position-y: 9px !important;">
                                  <option selected>AM</option>
                                  <option>PM</option>
                                </select>
                              </td>
                              <td><input type="text" name="MEAL_QTY[]" max="12" class="form-control"></td>
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
                              <input type="text" name="Review_of_Kitchen_Ingredients_Brand_Name[Salt]" class="form-control">
                            </li>
                            <li>Milk
                              <input type="text" name="Review_of_Kitchen_Ingredients_Brand_Name[Milk]" class="form-control">
                            </li>
                            <li>Ghee
                              <input type="text" name="Review_of_Kitchen_Ingredients_Brand_Name[Ghee]" class="form-control">
                            </li>
                            <li>Oil
                              <input type="text" name="Review_of_Kitchen_Ingredients_Brand_Name[Oil]" class="form-control">
                            </li>
                            <li>Wheat
                              <input type="text" name="Review_of_Kitchen_Ingredients_Brand_Name[Wheat]" class="form-control">
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>


                  <div class="form-group form-inner-radio-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Do you fast</label>

                      <div class="switch-field">
                        <input type="radio" id="radio-one101" name="Do_you_fast" value="yes" checked />
                        <label for="radio-one101">Yes</label>
                        <input type="radio" id="radio-two101" name="Do_you_fast" value="no" />
                        <label for="radio-two101">No</label>
                      </div>


                    </div>
                  </div>

                  <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">How many days in a week
                        <select name="How_many_days_in_a_week" style="width:100px;background-position-y: 9px !important;">
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                          <option>6</option>
                          <option>7</option>
                          <option>8</option>
                          <option>9</option>
                          <option>10</option>
                          <option>11</option>
                          <option>12</option>
                          <option>13</option>
                          <option>14</option>
                          <option>15</option>
                        </select></label>

                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Preference of Food/Likes</label>
                      <input type="text" class="form-control timepicker" name="Preference_of_FoodLikes" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Preference of Food/Dislikes</label>
                      <input type="text" class="form-control timepicker" name="Preference_of_FoodDislikes" />
                    </div>
                  </div>
                  <div class="form-group form-inner-area">
                    <p>TO BE FIILLED BY THE DIETICIAN</p>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">BMI</label>
                      <input type="text" class="form-control timepicker" name="BMI" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">IBW</label>
                      <input type="text" class="form-control timepicker" name="IBW" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Diet Goal</label>
                      <input type="text" class="form-control timepicker" name="Diet_Goal" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Calorie Needs</label>
                      <input type="text" class="form-control timepicker" name="Calorie_Needs" />
                    </div>
                  </div>
                  <div class="form-group form-inner-area">
                    <p>Note: This information will be kept confidential and used only for your health purpose.</p>
                  </div>
                  <!-- <div class="form-group form-inner-area row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Disclaimer</label>
                      <p> This information is solely intended to provide assistance to you in your personal healthy eating efforts. The information is not intended as a substitute for consultation, evaluation or treatment by a medical professional. This service provided by us is not intended to be, and should not be construed as an alternative to medical advice or as a substitute for medical or professional care and treatment.
                        Stop doing yoga asana immediately if you experience faintness, dizziness, pain or shortness of breath at any time. We hereon are not responsible for any injuries you may experience as a result of performing the physical exercises in wrong way. 
                        The subscriber has taken part in this program voluntarily and completely understands the methodology. Health issues, if any, arising to the subscriber during or after the duration of the program are completely the subscriber’s responsibility and in no condition can hold Homeomantra liable. <b>This Nutrition Program is Not Transferable, Not Extendable, Not Refundable</b> </p>
                    </div>
                  </div> -->
                </section>
              </div>
            </div>
          </div>
          <div class="actions">
            <ul>
              <li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK </span></li>
              <li><button class="js-btn-next" type="submit" title="NEXT">SUBMIT <i class="fa fa-arrow-right"></i></button></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>