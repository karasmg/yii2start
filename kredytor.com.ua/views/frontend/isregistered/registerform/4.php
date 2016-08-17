	<div class="widget-content tab-<?=$step;?> tab-content">
		<h2 class="h2-title"><?=Yii::t('site', 'Work data');?></h2>
		<div class="clearfix">		
			<?=$form->errorSummary( $model_anketa );?>
			<div class="left-form">
				<div class="widget-form">
					<div class="inp-block">
						<?php
							//Вид занятости
							$fieldName = 'prof';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->dropDownList($model_anketa, $fieldName, $selects[$fieldName][1], array('class'=>'select')); ?>
							<?php echo $error; ?>			
						</div>
					</div>
					<div class="if_works">
						<div class="inp-block">	
							<?php
								//Тип работника
								$fieldName = 'job_type';
								echo $form->labelEx($model_anketa, $fieldName); 
								$error = $form->error($model_anketa, $fieldName);
							?>
							<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
								<?php echo $form->dropDownList($model_anketa, $fieldName, $selects[$fieldName][1], array('class'=>'select')); ?>
								<?php echo $error; ?>			
							</div>						
						</div>
						<div class="if_works_flp">
							<div class="inp-block">	
								<?php
									//Вид деятельности организации
									$fieldName = 'job_orgtype';
									echo $form->labelEx($model_anketa, $fieldName); 
									$error = $form->error($model_anketa, $fieldName);
								?>
								<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
									<?php echo $form->dropDownList($model_anketa, $fieldName, $selects[$fieldName][1], array('class'=>'select')); ?>
									<?php echo $error; ?>			
								</div>						
							</div>
							<div class="inp-block">	
								<?php
									//Название организации
									$fieldName = 'job_flpname';
									echo $form->labelEx($model_anketa, $fieldName); 
									$error = $form->error($model_anketa, $fieldName);
								?>
								<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
									<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
									<?php echo $error; ?>			
								</div>						
							</div>
						</div>
						<div class="if_works_employee">
							<div class="inp-block">	
								<?php
									//Сфера деятельности
									$fieldName = 'job_shpere';
									echo $form->labelEx($model_anketa, $fieldName); 
									$error = $form->error($model_anketa, $fieldName);
								?>
								<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
									<?php echo $form->dropDownList($model_anketa, $fieldName, $selects[$fieldName][1], array('class'=>'select')); ?>
									<?php echo $error; ?>			
								</div>						
							</div>
							<div class="inp-block">	
								<?php
									//Название организации
									$fieldName = 'job_orgname';
									echo $form->labelEx($model_anketa, $fieldName); 
									$error = $form->error($model_anketa, $fieldName);
								?>
								<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
									<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
									<?php echo $error; ?>			
								</div>						
							</div>
							<div class="inp-block if_works">	
								<?php
									//Должность
									$fieldName = 'job_position';
									echo $form->labelEx($model_anketa, $fieldName); 
									$error = $form->error($model_anketa, $fieldName);
								?>
								<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
									<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
									<?php echo $error; ?>			
								</div>						
							</div>
						</div>
						<div class="inp-block">	
							<?php
								//Адрес
								$fieldName = 'job_addr';
								echo $form->labelEx($model_anketa, $fieldName); 
								$error = $form->error($model_anketa, $fieldName);
							?>
							<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
								<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
								<?php echo $error; ?>			
							</div>						
						</div>
						<div class="inp-block">	
							<?php
								//Рабочий стационарный телефон
								$fieldName = 'job_phone';
								echo $form->labelEx($model_anketa, $fieldName); 
								$error = $form->error($model_anketa, $fieldName);
							?>
							<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
								<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'0441234567', 'class'=>'inp-text homephone', 'maxlength'=>10)); ?>
								<?php echo $error; ?>			
							</div>						
						</div>
						<div class="if_works_employee">
							<div class="inp-block">	
								<?php
									//ФИО руководителя
									$fieldName = 'job_bossfio';
									echo $form->labelEx($model_anketa, $fieldName); 
									$error = $form->error($model_anketa, $fieldName);
								?>
								<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
									<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
									<?php echo $error; ?>			
								</div>						
							</div>
							<div class="inp-block">	
								<?php
									//Моб. тел руководителя
									$fieldName = 'job_bossphone';
									echo $form->labelEx($model_anketa, $fieldName); 
									$error = $form->error($model_anketa, $fieldName);
								?>
								<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
									<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'+380991234567', 'class'=>'inp-text mobilephone', 'maxlength'=>13)); ?>
									<?php echo $error; ?>			
								</div>						
							</div>
						</div>
						<div class="inp-block">	
							<?php
								//Стаж на последнем месте работы
								$fieldName = 'job_experiencethis';
								echo $form->labelEx($model_anketa, $fieldName); 
								$error = $form->error($model_anketa, $fieldName);
							?>
							<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
								<?php echo $form->dropDownList($model_anketa, $fieldName, $selects[$fieldName][1], array('class'=>'select')); ?>
								<?php echo $error; ?>			
							</div>						
						</div>
						<div class="inp-block">	
							<?php
								//Стаж общий
								$fieldName = 'job_experiencetotal';
								echo $form->labelEx($model_anketa, $fieldName); 
								$error = $form->error($model_anketa, $fieldName);
							?>
							<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
								<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
								<?php echo $error; ?>			
							</div>						
						</div>
						
					</div>	
					<div class="inp-block">	
						<?php
						//Основной доход
						$fieldName = 'job_primary_income';
						echo $form->labelEx($model_anketa, $fieldName); 
						$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text price')); ?><span class="priceUha"><?=Yii::t('site', 'uha');?></span>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
						//Дополнительный доход
						$fieldName = 'job_secondary_income';
						echo $form->labelEx($model_anketa, $fieldName); 
						$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text price')); ?><span class="priceUha"><?=Yii::t('site', 'uha');?></span>
							<?php echo $error; ?>			
						</div>						
					</div>
				</div>
			</div>
			
			<div class="right-form">	
				<div class="widget-form">
					<h2 class="h2-title"><?=Yii::t('site', 'Contact person');?> 1</h2>
					<div class="inp-block">	
						<?php
							//ФИО
							$fieldName = 'guarant1_fio';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Мобильный 1
							$fieldName = 'guarant1_phone_mobile';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'+380991234567', 'class'=>'inp-text mobilephone', 'maxlength'=>13)); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Мобильный 2
							$fieldName = 'guarant1_phone_mobile2';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'+380991234567', 'class'=>'inp-text mobilephone', 'maxlength'=>13)); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Вид родства
							$fieldName = 'guarant1_relationship';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->dropDownList($model_anketa, $fieldName, $selects[$fieldName][1], array('class'=>'select')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<h2 class="h2-title"><?=Yii::t('site', 'Contact person');?> 2</h2>
					<div class="inp-block">	
						<?php
							//ФИО
							$fieldName = 'guarant2_fio';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Мобильный 1
							$fieldName = 'guarant2_phone_mobile';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'+380991234567', 'class'=>'inp-text mobilephone', 'maxlength'=>13)); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Мобильный 2
							$fieldName = 'guarant2_phone_mobile2';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'+380991234567', 'class'=>'inp-text mobilephone', 'maxlength'=>13)); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Вид родства
							$fieldName = 'guarant2_relationship';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->dropDownList($model_anketa, $fieldName, $selects[$fieldName][1], array('class'=>'select')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
				</div>
			</div>		
			
			<div class="inp-block clear">
				<button class="btn btn-big" type="submit"><?=($isMenuDisabled ? Yii::t('site', 'next') : Yii::t('site', 'Edit data'));?></button>
			</div>
					
		</div>
			
	</div>