<div class="wrapper">
	<div class="steps-area steps-area-fixed">
		<div class="image-holder"> <img src="img/side-img.jpg" alt=""> </div>
		<div class="steps clearfix">
			<ul class="tablist multisteps-form__progress">
				<li class="multisteps-form__progress-btn js-active last"> <span>1</span>
					<h3 class="tab-title">Personal Information</h3>
				</li>
				<!-- <li class="multisteps-form__progress-btn"> <span>2</span>
          <h3 class="tab-title">Terms and Conditions</h3>
        </li>
        <li class="multisteps-form__progress-btn last"> <span>3</span>
          <h3 class="tab-title">Instructions for Yoga Practice</h3>
        </li> -->
			</ul>
		</div>
	</div>
	<form class="multisteps-form__form" action="<?php echo URL('/') ?>/api/submit_html_questinnaires" id="wizard" method="POST">
		<input type="hidden" name="questionnaire_id" value="4" />
		<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
		<div class="form-area position-relative">
			<!-- div 1 -->
			<div class="multisteps-form__panel js-active" data-animation="slideHorz">
				<div class="wizard-forms">
					<div class="inner pb-100 clearfix">
						<div class="form-content pera-content col-md-10">
							<div class="step-inner-content"> <span class="step-no">PHYSCIAL FITNESS QUESTIONNAIRE </span>
								<h5 class="mt-3 mb-3">Personal Information</h5>
								<section id="step_1">
									<div class="form-group form-inner-area row">
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Name</label>
											<input type="text" class="form-control" name="full_name" />
										</div>
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Age & DOB</label>
											<input type="date" class="form-control" name="dob" />
										</div>
									</div>
									<div class="form-group form-inner-area row">
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Height (feet - inches)</label>
											<select name="Height" style="width:70px;background-position-y: 9px !important;">
												<option selected>Feet</option>
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
											&nbsp;<select name="" style="width:70px;background-position-y: 9px !important;">
												<option selected>Inches</option>
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
											</select>
										</div>
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Weight (kg)</label>
											<select name="Weight" style="width:70px;background-position-y: 9px !important;">
												<?php for ($i = 1; $i <= 120; $i++) {
													echo '<option>' . $i . '</option>';
												} ?>
											</select>
										</div>
									</div>
									<h6 class="mt-5 mb-3">Major Concern for which you joined the program:</h6>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="checkbox-label">1. Have you ever done Yoga?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one1" name="everdoneYoga" value="yes" checked />
												<label for="radio-one1">Yes</label>
												<input type="radio" id="radio-two1" name="everdoneYoga" value="no" />
												<label for="radio-two1">No</label>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">If Yes, What form?</label>
											<input type="text" class="form-control" name="Whatform" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="checkbox-label">2. Do you exercise regularly?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one2" name="exerciseregularly" value="yes" checked />
												<label for="radio-one2">Yes</label>
												<input type="radio" id="radio-two2" name="exerciseregularly" value="no" />
												<label for="radio-two2">No</label>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">If Yes, In what form Yoga / Indoor / Outdoor / Gym?</label>
											<input type="text" class="form-control" name="formYoga" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="checkbox-label">3. How do you rate yourself in terms of flexibility? </label>
											<input type="radio" name="rate[]" value="Good">
											<label class="checkbox-label">Good</label>
											<input type="radio" name="rate[]" value="Average">
											<label class="checkbox-label">Average</label>
											<input type="radio" name="rate[]" value="Bad">
											<label class="checkbox-label">Bad</label>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="checkbox-label">4. Did any Doctor advice you any restriction of moments?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one3" name="restriction" value="yes" checked />
												<label for="radio-one3">Yes</label>
												<input type="radio" id="radio-two3" name="restriction" value="no" />
												<label for="radio-two3">No</label>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">If Yes, Why? (If you have difficulty on sitting on the floor or getting up from the floor please mention the same here)</label>
											<input type="text" class="form-control" name="difficultyonsitting" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">5. Do you have any medical conditions we should be aware of?
												<div class="switch-field">
													<input type="radio" id="radio-one351" name="medicalconditions" value="yes" checked />
													<label for="radio-one351">Yes</label>
													<input type="radio" id="radio-two351" name="medicalconditions" value="no" />
													<label for="radio-two351">No</label>
												</div>If yes, Please specify:
											</label>

											<input type="text" class="form-control" name="medicalconditions_details" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">6. Do you experience any numbness or tingling in any part of the body?<div class="switch-field">
													<input type="radio" id="radio-one361" name="numbness" value="yes" checked />
													<label for="radio-one361">Yes</label>
													<input type="radio" id="radio-two361" name="numbness" value="no" />
													<label for="radio-two361">No</label>
												</div></label>
											<input type="text" class="form-control" name="numbness_details" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="checkbox-label">7. Have you been diagnosed or have you ever experienced any of the following?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one4" name="diagnosed" value="yes" checked />
												<label for="radio-one4">Yes</label>
												<input type="radio" id="radio-two4" name="diagnosed" value="no" />
												<label for="radio-two4">No</label>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">If Yes, rate the intensity of the symptom from 1-10, 1 being the least and 10 being the highest</label>
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
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">8. Any recent injury or surgery (in the past 6 months) Any pain or stiffness related to that?
												<div class="switch-field">
													<input type="radio" id="radio-one36" name="recentinjury" value="yes" checked />
													<label for="radio-one36">Yes</label>
													<input type="radio" id="radio-two36" name="recentinjury" value="no" />
													<label for="radio-two36">No</label>
												</div>
											</label>
											<input type="text" class="form-control" name="recentinjury_details" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">9. What interests you the most?

												<div class="switch-field">
													<input type="radio" id="radio-one37" name="interests" value="Asana" checked />
													<label for="radio-one37">Asana</label>
													<input type="radio" id="radio-two37" name="interests" value="Pranayama" />
													<label for="radio-two37">Pranayama</label>
													<input type="radio" id="radio-three37" name="interests" value="Meditation" />
													<label for="radio-three37">Meditation</label>
													<input type="radio" id="radio-four37" name="interests" value="Relaxation" />
													<label for="radio-four37">Relaxation</label>
												</div>
											</label>
											<input type="text" class="form-control" name="interests_details" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">10. Are you working?
												<div class="switch-field">
													<input type="radio" id="radio-one91" name="working" value="yes" checked />
													<label for="radio-one91">Yes</label>
													<input type="radio" id="radio-two91" name="working" value="no" />
													<label for="radio-two91">No</label>
												</div>

												If so, what kind of job?
											</label>
											<input type="text" class="form-control" name="working_details" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-sm-12 col-lg-12">
											<label class="control-label">11. How did you hear about us?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one38" name="How" value="Doctor" checked />
												<label for="radio-one38">Doctor</label>
												<input type="radio" id="radio-two38" name="How" value="Friend" />
												<label for="radio-two38">Friend</label>
												<input type="radio" id="radio-three38" name="How" value="SocialMedia" />
												<label for="radio-three38">Social Media</label>
												<input type="radio" id="radio-four39" name="How" value="Website" />
												<label for="radio-four39">Website</label>
											</div>
											</label>
											<div class="row">
												<div class="col-md-6">
													<label class="control-label">Name</label>
													<input type="text" class="form-control" name="Howfull_name" />
												</div>
												<div class="col-md-6">
													<label class="control-label">Mobile Number</label>
													<input type="text" class="form-control" name="Howmobile" />
												</div>
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
							<li><button type="submit" title="NEXT">Submit <i class="fa fa-arrow-right"></i></button></li>
						</ul>
					</div>
				</div>
			</div>

		</div>
	</form>
</div>