	<div class="widget-content tab-<?=$step;?> tab-content">
		
		<div class="clearfix">	
			<?=$form->errorSummary( $model_anketa );?>			
			<div class="left-form">
			<h2 class="h2-title"><?=Yii::t('site', 'Registration data');?></h2>
				<div class="widget-form">
					<div class="inp-block">
						<?php
							//Область
							$fieldName = 'contact_region';
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
							//Район
							$fieldName = 'contact_area';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>strip_tags($labels[$fieldName]), 'class'=>'inp-text')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Город
							$fieldName = 'contact_city';
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
							//Улица
							$fieldName = 'contact_street';
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
							//Дом
							$fieldName = 'contact_house';
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
							//Корпус
							$fieldName = 'contact_corp';
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
							//Квартира
							$fieldName = 'contact_flat';
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
							//Длительность регистрации
							$fieldName = 'contact_livetime';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->dropDownList($model_anketa, $fieldName, $selects[$fieldName][1], array('class'=>'select')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
				</div>
				<div class="inp-block">	
						<label class="titntint"><input id="live_and_registration_same" type="checkbox"><?=Yii::t('site', 'Registration place the same as live');?></label>
				</div>
			</div>
			<div class="right-form">
			<h2 class="h2-title"><?=Yii::t('site', 'Livingplace data');?></h2>	
				<div class="widget-form">
						<div class="inp-block">
						<?php
							//Область
							$fieldName = 'live_region';
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
							//Район
							$fieldName = 'live_area';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>strip_tags($labels[$fieldName]), 'class'=>'inp-text')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Город
							$fieldName = 'live_city';
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
							//Улица
							$fieldName = 'live_street';
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
							//Дом
							$fieldName = 'live_house';
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
							//Корпус
							$fieldName = 'live_corp';
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
							//Квартира
							$fieldName = 'live_flat';
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
							//Длительность проживания
							$fieldName = 'live_livetime';
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
							//Статус жилья
							$fieldName = 'live_status';
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
		</div>
			<div class="inp-block clear">
				<button class="btn btn-big" type="submit"><?=($isMenuDisabled ? Yii::t('site', 'next') : Yii::t('site', 'Edit data'));?></button>
			</div>
			
		</div>