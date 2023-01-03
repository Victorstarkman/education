<?php
declare(strict_types=1);
?>
<style>
    .all {
        position: absolute;
        text-transform: uppercase;
        font-weight: bold;
        word-break: break-all;
    }
    .dateline{
        top: 70;
        margin-left:520px
    }
    .personFirstLine {
        top: 90px;
    }

    .personSecondLine {
        top:110px;
    }

    .personThirdLine {
        top:170px;
    }
    .personFourthLine {
        top:300px;
    }

    .resultsnrllFirstLine {
        top: 363px;
    }
    .resultsnrllSecondLine {
        top: 25px;

    }
    .resultsnrllThirdLine{
        top: 53px;

    }
    .resultsLicenseDeniedFirstLine{
        top: 445px;
    }
    .resultsLicenseDeniedSecondLine{
        top: 25px;
    }
   .resultsChangeDeniedFirstLine{
     top: 600px;
   }
   .resultsChangeDeniedSecondLine{
     top:25px;
   }
   .resultsChangeDeniedThirdLine{
    top: 29px;
   }
   .resultsChangeDeniedfourthLine{
    top: 83px;
   }
   .resultsProvisoryDeniedFirstLine{
    top: 740px;
   }
   .resultsProvisoryDeniedSecondLine{
    top: 820px;
   }
   .resultsRetirementFirstLine{
    top: 845px;
   }
   .resultsObservationsLine{
    top: 870px;
   }
    
</style>
<?php //debug($dataToShow);die();?>

<html>
<head><title>Export</title></head>
<body>
<div>
    <img src="<?= WWW_ROOT;?>img/formulario_juntas_medicas.jpg" style="max-width: 792px"/>
    <!-- PERSON -->
    <div style= "width: 200px" class="dateLine all"><?= date('d m Y')?></div>
    <div style="width: 200px;margin-left: 150px;" class="personFirstLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->name . ' ' . $dataToShow->patient->lastname; ?></p>
    </div>
    <div style="width: 200px;margin-left: 100px;" class="personSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->cuil ?></p>
    </div>
    <div style="width: 150px;margin-left: 400px;" class="personSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->phone!=''?$dataToShow->patient->phone:'No figura'  ?></p>
    </div>
    <div style="width: 150px;margin-left: 560px;" class="personSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->medical_center->district?></p>
    </div>
    <div class="personThirdLine all">
        <p style="word-break: break-word;">
            <?php
            if ($dataToShow->patient->job=='Docente') : ?>
                <span style="margin-left:316px;"><?= $dataToShow->textForPDF(); ?></span>
            <?php else : ?>
            <span style="margin-left: 435px;"><?= $dataToShow->textForPDF(); ?></span>
            <?php endif; ?>
        </p>
    </div>
    <!-- END PERSON -->
    <!-- LICENSE REASON -->
    <div class="personFourthLine all">
        <?php
                if ($dataToShow->licence_reason==1) : ?>
                    <span style="margin-left:144px;"><?= $dataToShow->textForPDF(); ?></span>
                <?php elseif($dataToShow->licence_reason==2) : ?>
                    <span style="margin-left: 329px;"><?= $dataToShow->textForPDF(); ?></span>
                <?php elseif($dataToShow->licence_reason==3) : ?>
                    <span style="margin-left: 435px;"><?= $dataToShow->textForPDF(); ?></span>
                <?php elseif($dataToShow->licence_reason==4) : ?>
                    <span style="margin-left: 646px;"><?= $dataToShow->textForPDF(); ?></span>
        <?php endif; ?>
    </div>
    <!-- END LICENCE REASON -->
    <!-- DICTAMEN A NRLL-->
    <?php if($dataToShow->status==2):?> <!-- ausente -->
          <div class="resultsnrllFirstLine all">
                <span style="margin-left:130px;"><?= $dataToShow->textForPDF(); ?></span>
                <div class="resultsnrllSecondLine all">
                    <span style="margin-left:130px;"><?= $dataToShow->getSpeciality()?></span>
                </div>
                <div class="resultsnrllThirdLine all">
                    <span style="margin-left:150px;"><?= $dataToShow->cie10_id?></span>
                </div>   
            </div>
    <?php  endif;?>
    <!-- DICTAMEN B Licencia -->
    <?php if(($dataToShow->status==3) && ($dataToShow->licence_reason == 1)):?> <!-- licencia negada 3 y 1-->
          <div class="resultsLicenseDeniedFirstLine all">
              <span style="margin-left:130px;"><?= $dataToShow->textForPDF(); ?></span>
              <span style="margin-left:305px;"><?= $dataToShow->textForPDF(); ?></span>       
            </div>
    <?php elseif(($dataToShow->status==4) && ($dataToShow->licence_reason == 1)):?> <!-- licencia otorgada cambiar a 4 y 1-->
        <div class="resultsLicenseDeniedFirstLine all">
            <span style="margin-left:130px;"><?= $dataToShow->textForPDF(); ?></span>
            <div class="resultsLicenseDeniedSecondLine all">
                <span style="margin-left: 95px;"><?= $dataToShow->startLicense->day;?></span>
                <span style="margin-left: 15px;"><?= $dataToShow->startLicense->month;?></span>
                <span style="margin-left: 12px;"><?= $dataToShow->startLicense->year;?></span>
                <?php $finishLicense= $dataToShow->startLicense->addDays($dataToShow->recommendedDays)?>
                <span style="margin-left:75px;"><?= $finishLicense->day;?></span>
                <span style="margin-left: 15px;"><?= $finishLicense->month;?></span>
                <span style="margin-left: 12px;"><?= $finishLicense->year;?></span>
            </div>
        </div>
    <?php  endif;?>
    <!-- END Licencia -->
    <!-- DICTAMEN C CAMBIO DE FUNCIONES  -->
    <?php if(($dataToShow->status==4) && ($dataToShow->licence_reason == 2)):?> <!--  cambio otorgado 4 2-->
        <div class="resultsChangeDeniedFirstLine all">
            <span style="margin-left:104px;"><?= $dataToShow->textForPDF(); ?></span>
            <div class="resultsChangeDeniedSecondLine all">
                <span style="margin-left: 95px;"><?= $dataToShow->startLicense->day;?></span>
                <span style="margin-left: 15px;"><?= $dataToShow->startLicense->month;?></span>
                <span style="margin-left: 12px;"><?= $dataToShow->startLicense->year;?></span>
                <?php $finishLicense= $dataToShow->startLicense->addDays($dataToShow->recommendedDays)?>
                <span style="margin-left:75px;"><?= $finishLicense->day;?></span>
                <span style="margin-left: 15px;"><?= $finishLicense->month;?></span>
                <span style="margin-left: 12px;"><?= $finishLicense->year;?></span>
                <?php if(!empty($dataToShow->interdiction)):?>
                    <div class="resultsChangeDeniedThirdLine all">
                        <span style="margin-left:140px"><?= $dataToShow->interdiction ?></span>
                    </div>

                <?php endif;?>
            </div>
        </div>
    <?php elseif(($dataToShow->status==3) && ($dataToShow->licence_reason ==2)):?> <!--cambio negado  3 2-->
        <div class="resultsChangeDeniedFirstLine all">
            <div class="resultsChangeDeniedfourthLine all">
                <span style="margin-left:104px;"><?= $dataToShow->textForPDF(); ?></span>
                <?php if(!empty($dataToShow->reinstatement)):?>
                            <span style="margin-left: 270px;"><?= $dataToShow->reinstatement->day;?></span>
                            <span style="margin-left: 15px;"><?= $dataToShow->reinstatement->month;?></span>
                            <span style="margin-left: 12px;"><?= $dataToShow->reinstatement->year;?></span>
                        <?php endif;?>
            </div>
        </div>      
    <?php  endif;?>
    <!-- FIN DE CAMBIO DE FUNCIONES -->    
    <!-- DICTAMEN SERVICIOS PROVISORIOS --> 
    <?php if(($dataToShow->status==4) && ($dataToShow->licence_reason == 4)):?> <!--  provisorio otorgado 4 4-->
        <div class="resultsProvisoryDeniedFirstLine all">
            <span style="margin-left:104px;"><?= $dataToShow->textForPDF(); ?></span>
            <div class="resultsChangeDeniedSecondLine all">
                <span style="margin-left: 95px;"><?= $dataToShow->startLicense->day;?></span>
                <span style="margin-left: 12px;"><?= $dataToShow->startLicense->month;?></span>
                <span style="margin-left: 12px;"><?= $dataToShow->startLicense->year;?></span>
                <?php $finishLicense= $dataToShow->startLicense->addDays($dataToShow->recommendedDays)?>
                <span style="margin-left: 77px;"><?= $finishLicense->day;?></span>
                <span style="margin-left: 15px;"><?= $finishLicense->month;?></span>
                <span style="margin-left: 12px;"><?= $finishLicense->year;?></span>
                <?php if(!empty($dataToShow->interdiction)):?>
                    <div class="resultsChangeDeniedThirdLine all">
                        <span style="margin-left:140px"><?= $dataToShow->interdiction ?></span>
                    </div>

                <?php endif;?>
            </div>
        </div>
    <?php elseif(($dataToShow->status==3) && ($dataToShow->licence_reason ==4)):?> <!--provisorio  denegado 3 4-->
        <div class="resultsProvisoryDeniedSecondLine all">
            <span style="margin-left:104px;"><?= $dataToShow->textForPDF(); ?></span>
        </div>      
    <?php  endif;?> 
    <!-- FIN DE SERVICIOS PROVISORIOS -->
    <!-- RETIRO POR INCAPACIDAD -->
        <?php if($dataToShow->retirement):?>
            <div class="resultsRetirementFirstLine all">
                <span style="margin-left:262px"><?= $dataToShow->textForPDF()?></span>
            </div>
        <?php else:?>
            <div class="resultsRetirementFirstLine all">
                <span style="margin-left:316px"><?= $dataToShow->textForPDF()?></span>
            </div>
        <?php endif;?>
    <!-- FIN DE RETIRO POR INCAPACIDAD -->
    <!-- OBSERVACIONES -->
    <?php if(!empty($dataToShow->observations)):?>
        <div class="resultsObservationsLine all">
            <p style="margin-left:100px;word-break: break-word;"><?= $dataToShow->observations; ?></p>
        </div>
    <?php endif;?>
    <!-- FIN DE OBSERVACIONES -->
</div>
</body>
</html>
