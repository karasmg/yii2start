<script>
	$(document).ready(function() {
		$( '.names' ).tooltip({
			position: {
				my: "center bottom-20",
				at: "center top",
				using: function( position, feedback ) {
					$( this ).css( position );
					$( "<div>" )
							.addClass( "arrow" )
							.addClass( feedback.vertical )
							.addClass( feedback.horizontal )
							.appendTo( this );
				}
			}
		});
		$('#Anketa_birth_date').change(function(){
			innTest();
		});
		$('#Anketa_iid').change(function(){
			innTest();
		});

	});
	function innTest(){
		if($('#Anketa_birth_date').val() != "" && $('#Anketa_iid').val() != ""){
			var birthDate = $('#Anketa_birth_date').val().split('.');
			var birthCode = Math.abs((+new Date(1900,00,00)-+new Date(birthDate[2],Number(birthDate[1])-1,birthDate[0]))/864e5).toFixed(0);
			if($('#Anketa_iid').val().substr(0, 5) != birthCode){
                $('.errorInn').show();
                $('#Anketa_iid').addClass('errorTxt');
                $('#Anketa_birth_date').addClass('errorTxt');
			} else {
                $('.errorInn').hide();
                $('#Anketa_iid').removeClass('errorTxt');
                $('#Anketa_birth_date').removeClass('errorTxt');
			}
		}
		if(typeof window.sendHeight == 'function') sendHeight();
	}
</script>
<div class="widget-content tab-<?=$step;?> tab-content">
		<h2 class="h2-title"><?=Yii::t('site', 'Personal data');?></h2>
		<div class="clearfix">
			<?=$form->errorSummary( $model_anketa );?>
            <div class="errorInn"><p><?= Yii::t('site','Check Inn and birth date')?></p></div>
			<div class="left-form">
				<div class="widget-form">
					<div class="inp-block">
						<?php
							//ИНН
							$fieldName = 'iid';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
							$disabled = array();
							if ( $model_anketa->scenario != 'insert' ) {
								$disabled = array('disabled'=>'disabled');
							}
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array_merge($disabled, array('placeholder'=>'1234567890', 'class'=>'inp-text inn'))); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block names">
						<?php
							//Фамилия
							$fieldName = 'surname';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text', 'title'=>Yii::t('site', 'Use Ukrainian, please'))); ?>
							<?php echo $error; ?>			
						</div>
					</div>
					<div class="inp-block">	
						<?php
							//Прежнее ФИО
							$fieldName = 'prev_fio';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>strip_tags($labels[$fieldName]), 'class'=>'inp-text')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block names">
						<?php
							//Имя
							$fieldName = 'name';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text', 'title'=>Yii::t('site', 'Use Ukrainian, please'))); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block names">
						<?php
							//Отчество
							$fieldName = 'lastname';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>$labels[$fieldName], 'class'=>'inp-text', 'title'=>Yii::t('site', 'Use Ukrainian, please'))); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">
						<?php
							//Дата рождения
							$fieldName = 'birth_date';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'__.__.____', 'class'=>'inp-text datepicker date')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
				</div>
				<div class="widget-form">
					<h2 class="h2-title"><?=Yii::t('site', 'Passport data');?></h2>
					<div class="inp-block">	
						<?php
							//Серия
							$fieldName = 'passport_seria';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'ТТ', 'class'=>'inp-text passport_seria')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Номер
							$fieldName = 'passport_number';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'200652', 'class'=>'inp-text passport_number')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">	
						<?php
							//Кем выдан
							$fieldName = 'passport_issued';
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
							//Дата выдачи
							$fieldName = 'passport_date';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'__.__.____', 'class'=>'inp-text datepicker date')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
				</div>
			</div>
			
			<div class="right-form">	
				<div class="widget-form">
					<div class="inp-block">	
						<?php
							//Пол
							$fieldName = 'sex';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->radioButtonList($model_anketa, $fieldName, $selects[$fieldName][1], array('class'=>'radiobox', 'template'=>'{input}{label}', 'separator'=>'')); ?>
							<?php echo $error; ?>			
						</div>						
					</div>
					<div class="inp-block">
						<?php
							//Гражданство
							$fieldName = 'cityzen';
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
							//Номер моб. телефона
							$fieldName = 'contact_phone_mobile';
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
							//Номер моб. телефона 2
							$fieldName = 'contact_phone_mobile2';
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
							//Имейл
							$fieldName = 'contact_email';
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
							//Номер дом. телефона
							$fieldName = 'contact_phone_home';
							echo $form->labelEx($model_anketa, $fieldName); 
							$error = $form->error($model_anketa, $fieldName);
						?>
						<div class="inp-box<?= ( $error ? ' error-field' : '' ); ?>">
							<?php echo $form->textField($model_anketa, $fieldName, array('placeholder'=>'0441234567', 'class'=>'inp-text homephone', 'maxlength'=>10)); ?>
							<?php echo $error; ?>			
						</div>
					</div>
				</div>
				<div class="widget-form">
					<h2 class="h2-title"><?=Yii::t('site', 'Social data');?></h2>
					<div class="inp-block">
						<?php
							//Семейное положение
							$fieldName = 'married';
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
							//Количество детей (до 18 лет)
							$fieldName = 'children';
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
				<button class="btn btn-big" type="submit"><?=( $isMenuDisabled ? Yii::t('site', 'next') : Yii::t('site', 'Edit data'));?></button>
			</div>
			
		</div>		
	</div>
