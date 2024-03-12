<div class="wrapper">
	<div class="steps-area steps-area-fixed">
		<div class="image-holder"> <img src="img/side-img.jpg" alt=""> </div>
		<div class="steps clearfix">
			<ul class="tablist multisteps-form__progress">
				<li class="multisteps-form__progress-btn js-active current"> <span>1</span>
					<h3 class="tab-title">General Information</h3>
				</li>
				<li class="multisteps-form__progress-btn"> <span>2</span>
					<h3 class="tab-title">Your Health</h3>
				</li>
				<li class="multisteps-form__progress-btn"> <span>3</span>
					<h3 class="tab-title">Your Mental Health</h3>
				</li>
				<li class="multisteps-form__progress-btn last"> <span>4</span>
					<h3 class="tab-title">Family History</h3>
				</li>
			</ul>
		</div>
	</div>
	<form class="multisteps-form__form" action="<?php echo URL('/') ?>/api/submit_html_questinnaires" id="wizard" method="POST">
		<input type="hidden" name="questionnaire_id" value="2" />
		<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
		<div class="form-area position-relative">
			<!-- div 1 -->
			<div class="multisteps-form__panel js-active" data-animation="slideHorz">
				<div class="wizard-forms">
					<div class="inner pb-100 clearfix">
						<div class="form-content pera-content col-md-10">
							<div class="step-inner-content"> <span class="step-no">INFERTILITY QUESTIONNAIRE</span>
								<p>The following questionnaire is designed to help us evaluate your Physical and Mental health. Please circle or fill in the appropriate answer. If you have any questions or additional comments, write them in the space provided.</p>
								<h5 class="mt-3">General Information</h5>
								<section id="step_1">
									<div class="form-group form-inner-area row">
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Full Name</label>
											<input type="text" class="form-control" name="full_name" />
										</div>
										<div class="col-md-3 col-lg-3 col-sm-3">
											<label class="control-label">DOB</label>
											<input type="date" class="form-control datepicker1" name="dob" />
										</div>
									</div>
									<div class="form-group form-inner-area row">
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Wife’s/Partner’s Name</label>
											<input type="text" class="form-control" name="partner_name" />
										</div>
										<div class="col-md-3 col-lg-3 col-sm-3">
											<label class="control-label">DOB</label>
											<input type="date" class="form-control datepicker1" name="partner_dob" />
										</div>
									</div>
									<div class="form-group row form-inner-area">
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Your Occupation</label>
											<input type="text" class="form-control" name="your_occupation" />
										</div>
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Wife’s/Partner’s Occupation</label>
											<input type="text" class="form-control" name="partner_occupation" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<h5>Fertility History</h5>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">Are you currently married or in a relationship?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one7" name="married_inrelationship" value="yes" checked />
												<label for="radio-one7">Yes</label>
												<input type="radio" id="radio-two7" name="married_inrelationship" value="no" />
												<label for="radio-two7">No</label>
											</div>
										</div>
									</div>
									<div class="form-group row form-inner-area">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<h5>Sexual History</h5>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">Do you have any problems with erection/lubrication</label>
											<div class="switch-field">
												<input type="radio" id="radio-one8" name="problem_with_erection" value="yes" checked />
												<label for="radio-one8">Yes</label>
												<input type="radio" id="radio-two8" name="problem_with_erection" value="no" />
												<label for="radio-two8">No</label>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">If yes:


												<div class="form-group  row">
													<div class="col-md-4 col-lg-4 col-sm-4">
														<input type="checkbox" name="problem_with_erection_details[]" value="Initiation">
														<label class="checkbox-label">Initiation</label>
													</div>
													<div class="col-md-4 col-lg-4 col-sm-4">
														<input type="checkbox" name="problem_with_erection_details[]" value="Rigidity">
														<label class="checkbox-label">Rigidity</label>
													</div>
													<div class="col-md-4 col-lg-4 col-sm-4">
														<input type="checkbox" name="problem_with_erection_details[]" value="Curvature">
														<label class="checkbox-label">Curvature</label>
													</div>
													<div class="col-md-4 col-lg-4 col-sm-4">
														<input type="checkbox" name="problem_with_erection_details[]" value="Duration">
														<label class="checkbox-label">Duration</label>
													</div>
													<div class="col-md-4 col-lg-4 col-sm-4">
														<input type="checkbox" name="problem_with_erection_details[]" value="Premature ejaculation">
														<label class="checkbox-label">Premature ejaculation</label>
													</div>
												</div>
											</label>
											<input type="text" class="form-control" name="" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">Are you timing intercourse with ovulation?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one9" name="timing_intercourse" value="yes" checked />
												<label for="radio-one9">Yes</label>
												<input type="radio" id="radio-two9" name="timing_intercourse" value="no" />
												<label for="radio-two9">No</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">How frequently do you have intercourse?</label>
											<input type="text" class="form-control" name="intercourse_frequency" />
										</div>
									</div>
									<div class="form-group row form-inner-area">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<h5>Childhood</h5>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12 form-inner-area">
											<label class="control-label">At what age was the onset of puberty?</label>
											<select name="puberty_age" style="width:100px;background-position-y: 9px !important;">
												<option selected>1year</option>
												<option>2years</option>
												<option>3years</option>
												<option>4years</option>
												<option>5years</option>
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
										</div>
									</div>
									<div class="form-group row form-inner-area">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<h5>Medical History</h5>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Do you have any medical problems? <div class="switch-field">
													<input type="radio" id="radio-one1" name="medical_problem" value="yes" checked />
													<label for="radio-one1">Yes</label>
													<input type="radio" id="radio-two1" name="medical_problem" value="no" />
													<label for="radio-two1">No</label>
												</div>
												If yes, what are they?</label>
											<input type="text" class="form-control" name="medical_problem_detail" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Do you take any medication? <div class="switch-field">
													<input type="radio" id="radio-one2" name="any_medication" value="yes" checked />
													<label for="radio-one2">Yes</label>
													<input type="radio" id="radio-two2" name="any_medication" value="no" />
													<label for="radio-two2">No</label>
												</div> If yes, what are they?</label>
											<input type="text" class="form-control" name="any_medication_detail" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Are you allergic to any medications? <div class="switch-field">
													<input type="radio" id="radio-one3" name="medication_allergy" value="yes" checked />
													<label for="radio-one3">Yes</label>
													<input type="radio" id="radio-two3" name="medication_allergy" value="no" />
													<label for="radio-two3">No</label>
												</div> If yes, what are they?</label>
											<input type="text" class="form-control" name="medication_allergy_detail" />
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
								<h5 class="mt-3 mb-3">Your health</h5>
								<section id="step_2">
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
																</select></td>
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
											<label class="control-label">Do you feel you have suffered depression or stress or anxiety due to the fertility treatment?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one1" name="anxiety_due_to_the_fertility_treatment" value="yes" checked />
												<label for="radio-one1">Yes</label>
												<input type="radio" id="radio-two1" name="anxiety_due_to_the_fertility_treatment" value="no" />
												<label for="radio-two1">No</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Do you have pressure from family of any sorts?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one2" name="pressure_from_family_of_any_sorts" value="yes" checked />
												<label for="radio-one2">Yes</label>
												<input type="radio" id="radio-two2" name="pressure_from_family_of_any_sorts" value="no" />
												<label for="radio-two2">No</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Are you over stressing yourself on the process of the treatment?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one3" name="stressing_yourself" value="yes" checked />
												<label for="radio-one3">Yes</label>
												<input type="radio" id="radio-two3" name="stressing_yourself" value="no" />
												<label for="radio-two3">No</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">How energetic do you consider yourself in the past 3 months?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one4" name="energetic" value="yes" checked />
												<label for="radio-one4">Yes</label>
												<input type="radio" id="radio-two4" name="energetic" value="no" />
												<label for="radio-two4">No</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Please state any particular areas, either physical or emotional, that you would like to gain help with from this Wellness program</label>
											<input type="text" class="form-control" name="gain_help" />
										</div>
									</div>
									<div class="form-group form-inner-area row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<h5>Surgical History</h5>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Have you had any previous operations? <div class="switch-field">
													<input type="radio" id="radio-one6" name="previous_operations" value="yes" checked />
													<label for="radio-one6">Yes</label>
													<input type="radio" id="radio-two6" name="previous_operations" value="no" />
													<label for="radio-two6">No</label>
												</div> If yes, what was performed?</label>
											<input type="text" class="form-control" name="previous_operations_performance" />
										</div>
									</div>
									<div class="form-group form-inner-area row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<h5>Exposure</h5>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<p>Have you had any exposure in the last six months to</p>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">a) Pesticides</label>
											<div class="switch-field">
												<input type="radio" id="radio-one36" name="Pesticides" value="yes" checked />
												<label for="radio-one36">Yes</label>
												<input type="radio" id="radio-two36" name="Pesticides" value="no" />
												<label for="radio-two36">No</label>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">b) Chemotherapy</label>
											<div class="switch-field">
												<input type="radio" id="radio-one37" name="Chemotherapy" value="yes" checked />
												<label for="radio-one37">Yes</label>
												<input type="radio" id="radio-two37" name="Chemotherapy" value="no" />
												<label for="radio-two37">No</label>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">c) Anabolic Steroids</label>
											<div class="switch-field">
												<input type="radio" id="radio-one38" name="Anabolic_Steroids" value="yes" checked />
												<label for="radio-one38">Yes</label>
												<input type="radio" id="radio-two38" name="Anabolic_Steroids" value="no" />
												<label for="radio-two38">No</label>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">d) High Temperatures</label>
											<div class="switch-field">
												<input type="radio" id="radio-one39" name="High_Temperatures" value="yes" checked />
												<label for="radio-one39">Yes</label>
												<input type="radio" id="radio-two39" name="High_Temperatures" value="no" />
												<label for="radio-two39">No</label>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">e) X-Rays</label>
											<div class="switch-field">
												<input type="radio" id="radio-one40" name="X-Rays" value="yes" checked />
												<label for="radio-one40">Yes</label>
												<input type="radio" id="radio-two40" name="X-Rays" value="no" />
												<label for="radio-two40">No</label>
											</div>
										</div>
									</div>
									<div class="form-group form-inner-area row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<h5>Social History</h5>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Do you consume alcohol?</label>
											<div class="switch-field">
												<input type="radio" id="radio-one41" name="alcohol" value="yes" checked />
												<label for="radio-one41">Yes</label>
												<input type="radio" id="radio-two41" name="alcohol" value="no" />
												<label for="radio-two41">No</label>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">If yes, how much per week?</label>
											<input type="text" class="form-control" name="alcohol_per_week" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<p>Do you presently smoke or have you ever smoked:</p>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">a) Cigarettes </label>
											<div class="switch-field">
												<input type="radio" id="radio-one42" name="Cigarettes" value="yes" checked />
												<label for="radio-one42">Yes</label>
												<input type="radio" id="radio-two42" name="Cigarettes" value="no" />
												<label for="radio-two42">No</label>
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
								<h5 class="mt-3 mb-3">Family History</h5>
								<section id="step_4">
									<div class="form-group form-inner-area row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<p>Does anyone in your family have a history of- (Please state your relationship with that person as well)</p>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">a) Infertility</label>
											<div class="switch-field">
												<input type="radio" id="radio-one43" name="Infertility" value="yes" checked />
												<label for="radio-one43">Yes</label>
												<input type="radio" id="radio-two43" name="Infertility" value="no" />
												<label for="radio-two43">No</label>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Relationship</label>
											<input type="text" class="form-control" name="InfertilityRelationship" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">b) Cystic Fibrosis</label>
											<div class="switch-field">
												<input type="radio" id="radio-one44" name="Cystic_Fibrosis" value="yes" checked />
												<label for="radio-one44">Yes</label>
												<input type="radio" id="radio-two44" name="Cystic_Fibrosis" value="no" />
												<label for="radio-two44">No</label>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Relationship</label>
											<input type="text" class="form-control" name="Cystic_FibrosisRelationship" />
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">c) Hormonal Imbalance</label>
											<div class="switch-field">
												<input type="radio" id="radio-one45" name="Hormonal_Imbalance" value="yes" checked />
												<label for="radio-one45">Yes</label>
												<input type="radio" id="radio-two45" name="Hormonal_Imbalance" value="no" />
												<label for="radio-two45">No</label>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Relationship</label>
											<input type="text" class="form-control" name="Hormonal_ImbalanceRelationship" />
										</div>
									</div>
									<div class="form-group form-inner-area row mt-5">
										<div class="col-md-12 col-lg-12 col-sm-12 mt-5">
											<h6 class="mb-3">Declaration</h6>
											<p class="control-label"><input type="checkbox" name="na" value="NA" required style="width: auto !important;
height: auto !important; margin-right:3px !important;">Hereby I am declaring that I have disclosed all information regarding my health relevant to the practice of yoga/exercise. I take full responsibility for all applications of yoga/exercise I practice in the class and outside the class. I fully understand that the recommendations, ideas or techniques expressed and described in the yoga classes cannot be regarded as a substitute for the advice of a qualified medical practitioner.I fully understand that the yoga/exercise specified is not a fertility treatment but to improve our mental and physical health and wellbeing. Any uses to which the recommendations, ideas and techniques are put are at my sole discretion and risk.</p>
										</div>
									</div>
								</section>
							</div>
						</div>
					</div>
					<div class="actions">
						<ul>
							<li><span class="js-btn-prev" title="BACK"><i class="fa fa-arrow-left"></i> BACK </span></li>
							<li><button type="submit"  title="NEXT">SUBMIT <i class="fa fa-arrow-right"></i></button></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>