<div class="wrapper">
  <div class="steps-area steps-area-fixed">
    <div class="image-holder"> <img src="img/side-img.jpg" alt=""> </div>
    <div class="steps clearfix">
      <ul class="tablist multisteps-form__progress">
        <li class="multisteps-form__progress-btn js-active current"> <span>1</span>
          <h3 class="tab-title">Personal Information</h3>
        </li>
        <li class="multisteps-form__progress-btn"> <span>2</span>
          <h3 class="tab-title">Your General Health</h3>
        </li>
        <li class="multisteps-form__progress-btn"> <span>3</span>
          <h3 class="tab-title">Your Mental Health</h3>
        </li>
        <li class="multisteps-form__progress-btn last"> <span>4</span>
          <h3 class="tab-title">A Little More About You</h3>
        </li>
      </ul>
    </div>
  </div>
  <form class="multisteps-form__form" action="<?php echo URL('/') ?>/api/submit_html_questinnaires" id="wizard" method="POST">
    <input type="hidden" name="questionnaire_id" value="5" />
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
    <div class="form-area position-relative">
      <!-- div 1 -->
      <div class="multisteps-form__panel js-active" data-animation="slideHorz">
        <div class="wizard-forms">
          <div class="inner pb-100 clearfix">
            <div class="form-content pera-content col-md-10">
              <div class="step-inner-content"> <span class="step-no">PRE-NATAL QUESTIONNIARE</span>
                <p>We believe in extraordinary care for extra-ordinary women, So please provide us with any valuable information that will help us to plan the Holistic approach that is beneficial and safe for you.</p>
                <h5 class="mt-3 mb-3">Personal Information</h5>
                <section id="step_1">
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Name</label>
                      <input type="text" class="form-control" name="full_name" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Age</label>
                      <input type="date" class="form-control" name="age" />
                    </div>
                  </div>
                  <div class="form-group form-inner-area row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Email</label>
                      <input type="text" class="form-control" name="email" />
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                      <label class="control-label">Contact No</label>
                      <input type="text" class="form-control" name="contact_no" />
                    </div>
                  </div>
                  <h5 class="mt-3 mb-3">Your Pregnancy</h5>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <div class="row ">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <label class="checkbox-label">Is this your first pregnancy</label>
                          <div class="switch-field">
                            <input type="radio" id="radio-one1" name="firstpregnancy" value="yes" checked />
                            <label for="radio-one1">Yes</label>
                            <input type="radio" id="radio-two1" name="firstpregnancy" value="no" />
                            <label for="radio-two1">No</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-sm-12 col-lg-4">
                      <label class="control-label">Expected Due Date (EDD) </label>
                      <input type="date" class="form-control" name="EDD" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-4 col-sm-12 col-lg-4">
                      <label class="control-label">Weeks Through</label>
                      <input type="text" class="form-control" name="Weeks" style="width: 80%!important; display:initial !important" />wks
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <label class="control-label">How many babies are you expecting</label>
                      <input type="text" class="form-control" name="babiesexpecting" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <label class="control-label">High / Low Risk pregnancy (if high risk state why)</label>
                      <input type="text" class="form-control" name="HighLowRiskpregnancy" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <label class="control-label">Weight Profile</label>
                    </div>
                    <div class="col-md-3 col-sm-3 col-lg-3">
                      <label class="control-label">Time of Conception</label>
                      <input type="text" class="form-control" name="Time" style="width: 80%!important; display:initial !important" />Kg
                    </div>
                    <div class="col-md-3 col-sm-3 col-lg-3">
                      <label class="control-label">First Trimester</label>
                      <input type="text" class="form-control" name="FirstTrimester" style="width: 80%!important; display:initial !important" />Kg
                    </div>
                    <div class="col-md-3 col-sm-3 col-lg-3">
                      <label class="control-label">Second Trimester</label>
                      <input type="text" class="form-control" name="SecondTrimester" style="width: 80%!important; display:initial !important" />Kg
                    </div>
                    <div class="col-md-3 col-sm-3 col-lg-3">
                      <label class="control-label">Third Trimester</label>
                      <input type="text" class="form-control" name="ThirdTrimester" style="width: 80%!important; display:initial !important" />Kg
                    </div>
                  </div>
                  <h5 class="mt-5 mb-3">Your health</h5>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <p>Have you been diagnosed or have you ever experienced any of the following? ( If Yes, rate the intensity of the symptom from 1-10, 1 being the least and 10 being the highest)</p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <div class="table-responeive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Symptoms</th>
                              <th>Yes / No</th>
                              <th>Intensity (1 - 10)</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Constipation</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one10" name="Constipation" value="yes" checked />
                                  <label for="radio-one10">Yes</label>
                                  <input type="radio" id="radio-two10" name="Constipation" value="no" />
                                  <label for="radio-two10">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Constipation_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Nosebleeds</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one11" name="Nosebleeds" value="yes" checked />
                                  <label for="radio-one11">Yes</label>
                                  <input type="radio" id="radio-two11" name="Nosebleeds" value="no" />
                                  <label for="radio-two11">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Nosebleeds_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Lower back pain</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one12" name="Lower_back_pain" value="yes" checked />
                                  <label for="radio-one12">Yes</label>
                                  <input type="radio" id="radio-two12" name="Lower_back_pain" value="no" />
                                  <label for="radio-two12">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Lower_back_pain_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Varicose veins</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one13" name="Varicose_veins" value="yes" checked />
                                  <label for="radio-one13">Yes</label>
                                  <input type="radio" id="radio-two13" name="Varicose_veins" value="no" />
                                  <label for="radio-two13">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Varicose_veins_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Depression</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one14" name="Depression" value="yes" checked />
                                  <label for="radio-one14">Yes</label>
                                  <input type="radio" id="radio-two14" name="Depression" value="no" />
                                  <label for="radio-two14">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Depression_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Bleeding</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one15" name="Bleeding" value="yes" checked />
                                  <label for="radio-one15">Yes</label>
                                  <input type="radio" id="radio-two15" name="Bleeding" value="no" />
                                  <label for="radio-two15">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Bleeding_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Stiff neck and shoulders</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one16" name="Stiff_neck_and_shoulders" value="yes" checked />
                                  <label for="radio-one16">Yes</label>
                                  <input type="radio" id="radio-two16" name="Stiff_neck_and_shoulders" value="no" />
                                  <label for="radio-two16">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Stiff_neck_and_shoulders_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Pubic pain/girdle pain</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one17" name="Pubic_pain" value="yes" checked />
                                  <label for="radio-one17">Yes</label>
                                  <input type="radio" id="radio-two17" name="Pubic_pain" value="no" />
                                  <label for="radio-two17">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Pubic_pain_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Headache</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one18" name="Headache" value="yes" checked />
                                  <label for="radio-one18">Yes</label>
                                  <input type="radio" id="radio-two18" name="Headache" value="no" />
                                  <label for="radio-two18">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Headache_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Dizziness/Fainting</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one19" name="Dizziness" value="yes" checked />
                                  <label for="radio-one19">Yes</label>
                                  <input type="radio" id="radio-two19" name="Dizziness" value="no" />
                                  <label for="radio-two19">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Dizziness_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Heartburn</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one20" name="Heartburn" value="yes" checked />
                                  <label for="radio-one20">Yes</label>
                                  <input type="radio" id="radio-two20" name="Heartburn" value="no" />
                                  <label for="radio-two20">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Heartburn_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Sciatica</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one21" name="Sciatica" value="yes" checked />
                                  <label for="radio-one21">Yes</label>
                                  <input type="radio" id="radio-two21" name="Sciatica" value="no" />
                                  <label for="radio-two21">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Sciatica_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Oedema (swollen joints or limbs)</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one22" name="Oedema" value="yes" checked />
                                  <label for="radio-one22">Yes</label>
                                  <input type="radio" id="radio-two22" name="Oedema" value="no" />
                                  <label for="radio-two22">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Oedema_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Anxiety</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one23" name="Anxiety" value="yes" checked />
                                  <label for="radio-one23">Yes</label>
                                  <input type="radio" id="radio-two23" name="Anxiety" value="no" />
                                  <label for="radio-two23">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Anxiety_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Aching joints</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one24" name="Aching_joints" value="yes" checked />
                                  <label for="radio-one24">Yes</label>
                                  <input type="radio" id="radio-two24" name="Aching_joints" value="no" />
                                  <label for="radio-two24">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Aching_joints_intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Carpel tunnel (wrist pain)</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one25" name="Carpel_tunnel" value="yes" checked />
                                  <label for="radio-one25">Yes</label>
                                  <input type="radio" id="radio-two25" name="Carpel_tunnel" value="no" />
                                  <label for="radio-two25">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Carpel_tunnel_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Anaemia</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one26" name="Anaemia" value="yes" checked />
                                  <label for="radio-one26">Yes</label>
                                  <input type="radio" id="radio-two26" name="Anaemia" value="no" />
                                  <label for="radio-two26">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Anaemia_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Asthma</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one27" name="Asthma" value="yes" checked />
                                  <label for="radio-one27">Yes</label>
                                  <input type="radio" id="radio-two27" name="Asthma" value="no" />
                                  <label for="radio-two27">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Asthma_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Sleep disturbances</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one28" name="Sleep_disturbances" value="yes" checked />
                                  <label for="radio-one28">Yes</label>
                                  <input type="radio" id="radio-two28" name="Sleep_disturbances" value="no" />
                                  <label for="radio-two28">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Sleep_disturbances_intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Piles</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one29" name="Piles" value="yes" checked />
                                  <label for="radio-one29">Yes</label>
                                  <input type="radio" id="radio-two29" name="Piles" value="no" />
                                  <label for="radio-two29">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Piles_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Cramps</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one30" name="Cramps" value="yes" checked />
                                  <label for="radio-one30">Yes</label>
                                  <input type="radio" id="radio-two30" name="Cramps" value="no" />
                                  <label for="radio-two30">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Cramps_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>High blood pressure</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one31" name="High_blood_pressure" value="yes" checked />
                                  <label for="radio-one31">Yes</label>
                                  <input type="radio" id="radio-two31" name="High_blood_pressure" value="no" />
                                  <label for="radio-two31">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="High_blood_pressure_intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Breathlessness</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one32" name="Breathlessness" value="yes" checked />
                                  <label for="radio-one32">Yes</label>
                                  <input type="radio" id="radio-two32" name="Breathlessness" value="no" />
                                  <label for="radio-two32">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Breathlessness_intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Insomnia</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one33" name="Insomnia" value="yes" checked />
                                  <label for="radio-one33">Yes</label>
                                  <input type="radio" id="radio-two33" name="Insomnia" value="no" />
                                  <label for="radio-two33">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Insomnia_intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Low blood pressure</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one34" name="Low_blood_pressure" value="yes" checked />
                                  <label for="radio-one34">Yes</label>
                                  <input type="radio" id="radio-two34" name="Low_blood_pressure" value="no" />
                                  <label for="radio-two34">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Low_blood_pressure_intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                            <tr>
                              <td>Diabetes</td>
                              <td>
                                <div class="switch-field">
                                  <input type="radio" id="radio-one35" name="Diabetes" value="yes" checked />
                                  <label for="radio-one35">Yes</label>
                                  <input type="radio" id="radio-two35" name="Diabetes" value="no" />
                                  <label for="radio-two35">No</label>
                                </div>
                              </td>
                              <td class="form-inner-area"><select name="Diabetes_Intensity" style="width:100px;background-position-y: 9px !important;">
                                  <option selected>0</option>
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
                                </select></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </section>

                <div class="form-group form-inner-radio-area row">
                  <div class="col-md-6 col-lg-6 col-sm-6">
                    <label class="control-label">Any allergies ?</label>
                    <div class="switch-field">
                      <input type="radio" id="radio-one361" name="allergies" value="yes" checked />
                      <label for="radio-one361">Yes</label>
                      <input type="radio" id="radio-two361" name="allergies" value="no" />
                      <label for="radio-two361">No</label>
                    </div>

                  </div>
                  <div class="col-md-6 col-lg-6 col-sm-6">
                    <label class="control-label">If Yes, give details </label>
                    <input type="text" class="form-control" name="early_morning_time">
                  </div>
                </div>

                <div class="col-md-12 col-lg-12 col-sm-12">
                  <label class="control-label">Other medical conditions (Please give details)</label>
                  <input type="text" class="form-control" name="Othermedicalconditions">
                </div>

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
                <h5 class="mt-3 mb-3">Your General Health</h5>
                <section id="step_2">
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">1. How would you describe your diet since you became pregnant? (Mark only one box) </label>
                      <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="diet[]" value="I eat both meat and fish">
                          <label class="checkbox-label">I eat both meat and fish</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="diet[]" value="I avoid meat, but eat fish">
                          <label class="checkbox-label">I avoid meat, but eat fish</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="diet[]" value="I avoid fish, but eat meat">
                          <label class="checkbox-label">I avoid fish, but eat meat</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="diet[]" value="I’m a vegetarian and include dairy products and eggs in my diet (ovo-lacto-vegetarian)">
                          <label class="checkbox-label">I’m a vegetarian and include dairy products and eggs in my diet (ovo-lacto-vegetarian)</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="diet[]" value="I’m a vegetarian and include dairy products but not eggs in my diet (lacto-vegetarian)">
                          <label class="checkbox-label">I’m a vegetarian and include dairy products but not eggs in my diet (lacto-vegetarian)</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="diet[]" value="I’m a vegetarian and avoid all dairy products and eggs (vegan)">
                          <label class="checkbox-label">I’m a vegetarian and avoid all dairy products and eggs (vegan)</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">2. How is your consumption of below mentioned food products since you became pregnant? (Mark only one box per line).</label>
                      <div class="table-responeive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Food Group</th>
                              <th>Seldom</th>
                              <th>Never</th>
                              <th>Sometimes</th>
                              <th>Often</th>
                              <th>Mostly</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Milk, Dairy Products, Cheese</td>
                              <td><input type="checkbox" name="Milk[Seldom]" value="1"></td>
                              <td><input type="checkbox" name="Milk[Never]" value="1"></td>
                              <td><input type="checkbox" name="Milk[Sometimes]" value="1"></td>
                              <td><input type="checkbox" name="Milk[Often]" value="1"></td>
                              <td><input type="checkbox" name="Milk[Mostly]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Bread and Cereals</td>
                              <td><input type="checkbox" name="Bread[Seldom]" value="1"></td>
                              <td><input type="checkbox" name="Bread[Never]" value="1"></td>
                              <td><input type="checkbox" name="Bread[Sometimes]" value="1"></td>
                              <td><input type="checkbox" name="Bread[Often]" value="1"></td>
                              <td><input type="checkbox" name="Bread[Mostly]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Eggs</td>
                              <td><input type="checkbox" name="Eggs[Seldom]" value="1"></td>
                              <td><input type="checkbox" name="Eggs[Never]" value="1"></td>
                              <td><input type="checkbox" name="Eggs[Sometimes]" value="1"></td>
                              <td><input type="checkbox" name="Eggs[Often]" value="1"></td>
                              <td><input type="checkbox" name="Eggs[Mostly]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Fruit</td>
                              <td><input type="checkbox" name="Fruit[Seldom]" value="1"></td>
                              <td><input type="checkbox" name="Fruit[Never]" value="1"></td>
                              <td><input type="checkbox" name="Fruit[Sometimes]" value="1"></td>
                              <td><input type="checkbox" name="Fruit[Often]" value="1"></td>
                              <td><input type="checkbox" name="Fruit[Mostly]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Meat</td>
                              <td><input type="checkbox" name="Meat[Seldom]" value="1"></td>
                              <td><input type="checkbox" name="Meat[Never]" value="1"></td>
                              <td><input type="checkbox" name="Meat[Sometimes]" value="1"></td>
                              <td><input type="checkbox" name="Meat[Often]" value="1"></td>
                              <td><input type="checkbox" name="Meat[Mostly]" value="1"></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <h5 class="mt-3 mb-3">Your Meal Pattern</h5>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">How often have you had the following meals per week since you became pregnant? A snack is a smaller meal consisting of for example a fruit, biscuit, bun, cake, yoghurt or sweets/candy. (Mark only one bx per line).</label>
                      <div class="table-responeive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Number of meals per week</th>
                              <th>7</th>
                              <th>6</th>
                              <th>5</th>
                              <th>4</th>
                              <th>3</th>
                              <th>2</th>
                              <th>1</th>
                              <th>0</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Breakfast</td>
                              <td><input type="checkbox" name="Breakfast[7]" value="1"></td>
                              <td><input type="checkbox" name="Breakfast[6]" value="1"></td>
                              <td><input type="checkbox" name="Breakfast[5]" value="1"></td>
                              <td><input type="checkbox" name="Breakfast[4]" value="1"></td>
                              <td><input type="checkbox" name="Breakfast[3]" value="1"></td>
                              <td><input type="checkbox" name="Breakfast[2]" value="1"></td>
                              <td><input type="checkbox" name="Breakfast[1]" value="1"></td>
                              <td><input type="checkbox" name="Breakfast[0]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Snack, Morning</td>
                              <td><input type="checkbox" name="Snack[7]" value="1"></td>
                              <td><input type="checkbox" name="Snack[6]" value="1"></td>
                              <td><input type="checkbox" name="Snack[5]" value="1"></td>
                              <td><input type="checkbox" name="Snack[4]" value="1"></td>
                              <td><input type="checkbox" name="Snack[3]" value="1"></td>
                              <td><input type="checkbox" name="Snack[2]" value="1"></td>
                              <td><input type="checkbox" name="Snack[1]" value="1"></td>
                              <td><input type="checkbox" name="Snack[0]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Lunch</td>
                              <td><input type="checkbox" name="Lunch[7]" value="1"></td>
                              <td><input type="checkbox" name="Lunch[6]" value="1"></td>
                              <td><input type="checkbox" name="Lunch[5]" value="1"></td>
                              <td><input type="checkbox" name="Lunch[4]" value="1"></td>
                              <td><input type="checkbox" name="Lunch[3]" value="1"></td>
                              <td><input type="checkbox" name="Lunch[2]" value="1"></td>
                              <td><input type="checkbox" name="Lunch[1]" value="1"></td>
                              <td><input type="checkbox" name="Lunch[0]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Snack, Afternoon </td>
                              <td><input type="checkbox" name="Afternoon[7]" value="1"></td>
                              <td><input type="checkbox" name="Afternoon[6]" value="1"></td>
                              <td><input type="checkbox" name="Afternoon[5]" value="1"></td>
                              <td><input type="checkbox" name="Afternoon[4]" value="1"></td>
                              <td><input type="checkbox" name="Afternoon[3]" value="1"></td>
                              <td><input type="checkbox" name="Afternoon[2]" value="1"></td>
                              <td><input type="checkbox" name="Afternoon[1]" value="1"></td>
                              <td><input type="checkbox" name="Afternoon[0]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Dinner</td>
                              <td><input type="checkbox" name="Dinner[7]" value="1"></td>
                              <td><input type="checkbox" name="Dinner[6]" value="1"></td>
                              <td><input type="checkbox" name="Dinner[5]" value="1"></td>
                              <td><input type="checkbox" name="Dinner[4]" value="1"></td>
                              <td><input type="checkbox" name="Dinner[3]" value="1"></td>
                              <td><input type="checkbox" name="Dinner[2]" value="1"></td>
                              <td><input type="checkbox" name="Dinner[1]" value="1"></td>
                              <td><input type="checkbox" name="Dinner[0]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Snack, Evening </td>
                              <td><input type="checkbox" name="Evening[7]" value="1"></td>
                              <td><input type="checkbox" name="Evening[6]" value="1"></td>
                              <td><input type="checkbox" name="Evening[5]" value="1"></td>
                              <td><input type="checkbox" name="Evening[4]" value="1"></td>
                              <td><input type="checkbox" name="Evening[3]" value="1"></td>
                              <td><input type="checkbox" name="Evening[2]" value="1"></td>
                              <td><input type="checkbox" name="Evening[1]" value="1"></td>
                              <td><input type="checkbox" name="Evening[0]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Supper</td>
                              <td><input type="checkbox" name="Supper[7]" value="1"></td>
                              <td><input type="checkbox" name="Supper[6]" value="1"></td>
                              <td><input type="checkbox" name="Supper[5]" value="1"></td>
                              <td><input type="checkbox" name="Supper[4]" value="1"></td>
                              <td><input type="checkbox" name="Supper[3]" value="1"></td>
                              <td><input type="checkbox" name="Supper[2]" value="1"></td>
                              <td><input type="checkbox" name="Supper[1]" value="1"></td>
                              <td><input type="checkbox" name="Supper[0]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Night Meal</td>
                              <td><input type="checkbox" name="Night[7]" value="1"></td>
                              <td><input type="checkbox" name="Night[6]" value="1"></td>
                              <td><input type="checkbox" name="Night[5]" value="1"></td>
                              <td><input type="checkbox" name="Night[4]" value="1"></td>
                              <td><input type="checkbox" name="Night[3]" value="1"></td>
                              <td><input type="checkbox" name="Night[2]" value="1"></td>
                              <td><input type="checkbox" name="Night[1]" value="1"></td>
                              <td><input type="checkbox" name="Night[0]" value="1" value="1"></td>
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
                <h5 class="mt-3 mb-3">Your Mental Health</h5>
                <section id="step_3">
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Here we believe Mental health is as important as physical health so please help us understand you better</label>
                      <div class="table-responeive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>&nbsp;</th>
                              <th>Always</th>
                              <th>Often</th>
                              <th>Rare</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>How often do you feel stressed</td>
                              <td><input type="checkbox" name="stressed[Always]" value="1"></td>
                              <td><input type="checkbox" name="stressed[Often]" value="1"></td>
                              <td><input type="checkbox" name="stressed[Rare]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Do you get upset/angry/irritable/edgy</td>
                              <td><input type="checkbox" name="edgy[Always]" value="1"></td>
                              <td><input type="checkbox" name="edgy[Often]" value="1"></td>
                              <td><input type="checkbox" name="edgy[Rare]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Do you find yourself ruminating about things</td>
                              <td><input type="checkbox" name="ruminating[Always]" value="1"></td>
                              <td><input type="checkbox" name="ruminating[Often]" value="1"></td>
                              <td><input type="checkbox" name="ruminating[Rare]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Are you worried/scared/frightened about your pregnancy</td>
                              <td><input type="checkbox" name="pregnancy[Always]" value="1"></td>
                              <td><input type="checkbox" name="pregnancy[Often]" value="1"></td>
                              <td><input type="checkbox" name="pregnancy[Rare]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Are you worried/scared/frightened about labour</td>
                              <td><input type="checkbox" name="labour[Always]" value="1"></td>
                              <td><input type="checkbox" name="labour[Often]" value="1"></td>
                              <td><input type="checkbox" name="labour[Rare]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Are you concerned about the babies nutrition</td>
                              <td><input type="checkbox" name="nutrition[Always]" value="1"></td>
                              <td><input type="checkbox" name="nutrition[Often]" value="1"></td>
                              <td><input type="checkbox" name="nutrition[Rare]" value="1"></td>
                            </tr>
                            <tr>
                              <td>Are you concerned about your weight gain</td>
                              <td><input type="checkbox" name="weight[Always]" value="1"></td>
                              <td><input type="checkbox" name="weight[Often]" value="1"></td>
                              <td><input type="checkbox" name="weight[Rare]" value="1"></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <h5 class="mt-3 mb-3">Pre-Natal Yoga Class</h5>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Please let me know what aspects of pregnancy yoga would interest you.</label>
                      <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="pregnancyyoga[]" value="Breathing exercises">
                          <label class="checkbox-label">Breathing exercises</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="pregnancyyoga[]" value="Strengthening and toning muscles">
                          <label class="checkbox-label">Strengthening and toning muscles</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="pregnancyyoga[]" value="Relieving specific ailments">
                          <label class="checkbox-label">Relieving specific ailments</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="pregnancyyoga[]" value="Quiet time to bond with baby">
                          <label class="checkbox-label">Quiet time to bond with baby</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="pregnancyyoga[]" value="Interacting with other To-be-Moms">
                          <label class="checkbox-label">Interacting with other To-be-Moms</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="pregnancyyoga[]" value="Relaxation/meditation">
                          <label class="checkbox-label">Relaxation/meditation</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="pregnancyyoga[]" value="Supporting good posture through pregnancy">
                          <label class="checkbox-label">Supporting good posture through pregnancy</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="pregnancyyoga[]" value="Preparing your body for birth">
                          <label class="checkbox-label">Preparing your body for birth</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                          <input type="checkbox" name="pregnancyyoga[]" value="Other reasons">
                          <label class="checkbox-label">Other reasons</label>
                          <input type="text" class="form-control">
                        </div>
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
                <h5 class="mt-3 mb-3">A Little More About You</h5>
                <section id="step_4">
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Have you ever had a miscarriage?<div class="switch-field">
                          <input type="radio" id="radio-one362" name="miscarriage" value="yes" checked />
                          <label for="radio-one362">Yes</label>
                          <input type="radio" id="radio-two362" name="miscarriage" value="no" />
                          <label for="radio-two362">No</label>
                        </div></label>
                      <input type="text" class="form-control" name="miscarriage_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Do you smoke, or have you ever smoked?
                        <div class="switch-field">
                          <input type="radio" id="radio-one363" name="smoked" value="yes" checked />
                          <label for="radio-one363">Yes</label>
                          <input type="radio" id="radio-two363" name="smoked" value="no" />
                          <label for="radio-two363">No</label>
                        </div>
                      </label>
                      <input type="text" class="form-control" name="smoked_details" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Have you practised yoga before?
                        <div class="switch-field">
                          <input type="radio" id="radio-one364" name="practisedyoga" value="yes" checked />
                          <label for="radio-one364">Yes</label>
                          <input type="radio" id="radio-two364" name="practisedyoga" value="no" />
                          <label for="radio-two364">No</label>
                        </div>
                        (Please give details)
                      </label>
                      <textarea name="practisedyoga_details" cols="" rows="3" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <label class="control-label">Have you had any physical injuries either in the past or present?)</label>
                      <div class="switch-field">
                        <input type="radio" id="radio-one365" name="injuries" value="yes" checked />
                        <label for="radio-one365">Yes</label>
                        <input type="radio" id="radio-two365" name="injuries" value="no" />
                        <label for="radio-two365">No</label>
                      </div>

                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <label class="control-label">(Please give details</label>
                      <input type="text" class="form-control" name="surgeries_underwent_details" />
                    </div>
                  </div>
                  <h6 class="mt-5">Declaration</h6>
                  <p class="control-label"><input type="checkbox" name="na" value="NA" style="width: auto !important;
height: auto !important; margin-right:3px !important;">Hereby I am declaring that I have disclosed all information regarding my health relevant to the practice of yoga during my pregnancy. I take full responsibility for all applications of yoga I practice in the class and outside the class during my pregnancy. I fully understand that the recommendations, ideas or techniques expressed and described in the pregnancy yoga classes cannot be regarded as a substitute for the advice of a qualified medical practitioner. Any uses to which the recommendations, ideas and techniques are put are at my sole discretion and risk</p>
                </section>
              </div>
            </div>
          </div>
          <div class="actions">
            <ul>
              <li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK </span></li>
              <li><button type="submit" title="NEXT">SUBMIT <i class="fa fa-arrow-right"></i></button></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>