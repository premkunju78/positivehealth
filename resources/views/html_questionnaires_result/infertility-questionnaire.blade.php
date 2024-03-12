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
	<form class="multisteps-form__form" action="#" id="wizard" method="POST">
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
											<p>{{$result['full_name'] ?? '-'}}</p>
										</div>
										<div class="col-md-3 col-lg-3 col-sm-3">
											<label class="control-label">DOB</label>
											<p>{{$result['dob'] ?? '-'}}</p>
										</div>
									</div>
									<div class="form-group form-inner-area row">
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Wife’s/Partner’s Name</label>
											<p>{{$result['partner_name'] ?? '-'}}</p>
										</div>
										<div class="col-md-3 col-lg-3 col-sm-3">
											<label class="control-label">DOB</label>
											<p>{{$result['partner_dob'] ?? '-'}}</p>
										</div>
									</div>
									<div class="form-group row form-inner-area">
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Your Occupation</label>
											<p>{{$result['your_occupation'] ?? '-'}}</p>
										</div>
										<div class="col-md-6 col-lg-6 col-sm-6">
											<label class="control-label">Wife’s/Partner’s Occupation</label>
											<p>{{$result['partner_occupation'] ?? '-'}}</p>
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
											<p>{{$result['married_inrelationship'] ?? '-'}}</p>

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
											<p>{{$result['problem_with_erection'] ?? '-'}}</p>

										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">If yes:


												<div class="form-group  row">
													<?php
													if (!empty($result['problem_with_erection_details'])) {
														foreach ($result['problem_with_erection_details'] as $problem_with_erection_details) { ?>
															<div class="col-md-4 col-lg-4 col-sm-4">
																<p><?php echo $problem_with_erection_details; ?></p>
															</div>
													<?php }
													}
													?>
												</div>
											</label>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">Are you timing intercourse with ovulation?</label>
											<p>{{$result['timing_intercourse'] ?? '-'}}</p>

										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">How frequently do you have intercourse?</label>
											<p>{{$result['intercourse_frequency'] ?? '-'}}</p>
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
											<p>{{$result['puberty_age'] ?? '-'}}</p>

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
													<p>{{$result['medical_problem'] ?? '-'}}</p>

												</div>
												If yes, what are they?</label>
											<p>{{$result['medical_problem_detail'] ?? '-'}}</p>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Do you take any medication?
												<p>{{$result['any_medication'] ?? '-'}}</p>
												If yes, what are they?
											</label>
											<p>{{$result['any_medication_detail'] ?? '-'}}</p>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Are you allergic to any medications?
												<p>{{$result['medication_allergy'] ?? '-'}}</p>
												If yes, what are they?
											</label>
											<p>{{$result['medication_allergy_detail'] ?? '-'}}</p>
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
																<p>{{$result['Constipation'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Constipation_Intensity'] ?? '-'}}</p>

															</td>
														</tr>
														<tr>
															<td>Nosebleeds</td>
															<td>
																<p>{{$result['Nosebleeds'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Nosebleeds_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Lower back pain</td>
															<td>
																<p>{{$result['Lower_back_pain'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Lower_back_pain_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Varicose veins</td>
															<td>
																<p>{{$result['Varicose_veins'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Varicose_veins_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Depression</td>
															<td>
																<p>{{$result['Depression'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Depression_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Bleeding</td>
															<td>
																<p>{{$result['Bleeding'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Bleeding_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Stiff neck and shoulders</td>
															<td>
																<p>{{$result['Stiff_neck_and_shoulders'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Stiff_neck_and_shoulders_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Pubic pain/girdle pain</td>
															<td>
																<p>{{$result['Pubic_pain'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Pubic_pain_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Headache</td>
															<td>
																<p>{{$result['Headache'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Headache_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Dizziness/Fainting</td>
															<td>
																<p>{{$result['Dizziness'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Dizziness_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Heartburn</td>
															<td>
																<p>{{$result['Heartburn'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Heartburn_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Sciatica</td>
															<td>
																<p>{{$result['Sciatica'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Sciatica_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Oedema (swollen joints or limbs)</td>
															<td>
																<p>{{$result['Oedema'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Oedema_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Anxiety</td>
															<td>
																<p>{{$result['Anxiety'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Anxiety_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Aching joints</td>
															<td>
																<p>{{$result['Aching_joints'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Aching_joints_intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Carpel tunnel (wrist pain)</td>
															<td>
																<p>{{$result['Carpel_tunnel'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Carpel_tunnel_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Anaemia</td>
															<td>
																<p>{{$result['Anaemia'] ?? '-'}}</p>
																<div class="switch-field">
																	<input type="radio" id="radio-one26" name="Anaemia" value="yes" checked />
																	<label for="radio-one26">Yes</label>
																	<input type="radio" id="radio-two26" name="Anaemia" value="no" />
																	<label for="radio-two26">No</label>
																</div>
															</td>
															<td class="form-inner-area">
																<p>{{$result['Anaemia_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Asthma</td>
															<td>
																<p>{{$result['Asthma'] ?? '-'}}</p>
																<div class="switch-field">
																	<input type="radio" id="radio-one27" name="Asthma" value="yes" checked />
																	<label for="radio-one27">Yes</label>
																	<input type="radio" id="radio-two27" name="Asthma" value="no" />
																	<label for="radio-two27">No</label>
																</div>
															</td>
															<td class="form-inner-area">
																<p>{{$result['Asthma_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Sleep disturbances</td>
															<td>
																<p>{{$result['Sleep_disturbances'] ?? '-'}}</p>
															</td>
															<td class="form-inner-area">
																<p>{{$result['Sleep_disturbances_intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Piles</td>
															<td>
																<p>{{$result['Piles'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Piles_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Cramps</td>
															<td>
																<p>{{$result['Cramps'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Cramps_Intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>High blood pressure</td>
															<td>
																<p>{{$result['High_blood_pressure'] ?? '-'}}</p>
															</td>
															<td class="form-inner-area">
																<p>{{$result['High_blood_pressure_intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Breathlessness</td>
															<td>
																<p>{{$result['Breathlessness'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Breathlessness_intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Insomnia</td>
															<td>
																<p>{{$result['Insomnia'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Insomnia_intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Low blood pressure</td>
															<td>
																<p>{{$result['Low_blood_pressure'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Low_blood_pressure_intensity'] ?? '-'}}</p>
															</td>
														</tr>
														<tr>
															<td>Diabetes</td>
															<td>
																<p>{{$result['Diabetes'] ?? '-'}}</p>

															</td>
															<td class="form-inner-area">
																<p>{{$result['Diabetes_Intensity'] ?? '-'}}</p>
															</td>
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
											<p>{{$result['anxiety_due_to_the_fertility_treatment'] ?? '-'}}</p>

										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Do you have pressure from family of any sorts?</label>
											<p>{{$result['pressure_from_family_of_any_sorts'] ?? '-'}}</p>

										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Are you over stressing yourself on the process of the treatment?</label>
											<p>{{$result['stressing_yourself'] ?? '-'}}</p>

										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">How energetic do you consider yourself in the past 3 months?</label>
											<p>{{$result['energetic'] ?? '-'}}</p>

										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Please state any particular areas, either physical or emotional, that you would like to gain help with from this Wellness program</label>
											<p>{{$result['gain_help'] ?? '-'}}</p>
										</div>
									</div>
									<div class="form-group form-inner-area row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<h5>Surgical History</h5>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Have you had any previous operations?
												<p>{{$result['previous_operations'] ?? '-'}}</p>
												If yes, what was performed?
											</label>
											<p>{{$result['previous_operations_performance'] ?? '-'}}</p>
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
											<p>{{$result['Pesticides'] ?? '-'}}</p>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">b) Chemotherapy</label>
											<p>{{$result['Chemotherapy'] ?? '-'}}</p>

										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">c) Anabolic Steroids</label>
											<p>{{$result['Anabolic_Steroids'] ?? '-'}}</p>

										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">d) High Temperatures</label>
											<p>{{$result['High_Temperatures'] ?? '-'}}</p>

										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">e) X-Rays</label>
											<p>{{$result['X-Rays'] ?? '-'}}</p>
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
											<p>{{$result['alcohol'] ?? '-'}}</p>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">If yes, how much per week?</label>
											<p>{{$result['alcohol_per_week'] ?? '-'}}</p>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<p>Do you presently smoke or have you ever smoked:</p>
										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="checkbox-label">a) Cigarettes </label>
											<p>{{$result['Cigarettes'] ?? '-'}}</p>

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
											<p>{{$result['Infertility'] ?? '-'}}</p>

										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Relationship</label>
											<p>{{$result['InfertilityRelationship'] ?? '-'}}</p>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">b) Cystic Fibrosis</label>
											<p>{{$result['Cystic_Fibrosis'] ?? '-'}}</p>

										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Relationship</label>
											<p>{{$result['Cystic_FibrosisRelationship'] ?? '-'}}</p>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">c) Hormonal Imbalance</label>
											<p>{{$result['Hormonal_Imbalance'] ?? '-'}}</p>

										</div>
										<div class="col-md-12 col-lg-12 col-sm-12">
											<label class="control-label">Relationship</label>
											<p>{{$result['Hormonal_ImbalanceRelationship'] ?? '-'}}</p>
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