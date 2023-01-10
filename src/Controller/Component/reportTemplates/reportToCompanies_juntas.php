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
    
    .personFirstLine {
        top: 107px;
    }

    .personSecondLine {
        top:130px;
    }

    .personThirdLine {
        top:163px;
    }
    .personFourthLine {
        top:300px;
    }
    .resultsFirstLineDictamen{
        top: 345px;
    }
    .resultsSecondLineDictamen{
        top: 370px;
    }
    .resultsnrllFirstLine {
        top: 412px;
    }
    .resultsnrllThirdLine{
        top: 53px;

    }
    .resultsLicenseDeniedFirstLine{
        top: 443px;
    }
    .resultsLicenceAcceptedFirstLine{
        top:25px;
    }
    .resultsLicenseAcceptedSecondLine{
        top:53px;
    }
    .resultsLicenseDeniedSecondLine{
        top: 82px;
    }
   .resultsChangeAcceptedFirstLine{
     top: 582px;
   }
   .resultsChangeAcceptedSecondLine{
     top:25px;
   }
   .resultsChangeAcceptedThirdLine{
      top:55px
   }
   .resultsChangeDeniedFirstLine{
    top: 665px;
   }
   .resultsChangeDeniedfourthLine{
    top: 83px;
   }
   .resultsProvisoryAcceptedFirstLine{
    top: 720px;
   }
   .resultsProvisoryAcceptedSecondLine{
    top: 30px;
   }
   .resultsProvisoryAcceptedThirdLine{
    top:55px;
   }
   .resultsProvisoryAcceptedForthLine{
    top: 805px;
   }
   .resultsRetirementFirstLine{
    top: 833px;
   }
   .resultsObservationsLine{
    top: 865px;
   }
    
</style>
<?php //debug($dataToShow);die();?>

<html>
<head><title>Export</title></head>
<body>
<div>
    <img src="<?= WWW_ROOT;?>img/Junta_medica_2023.jpg" style="max-width: 792px"/>
       <!-- PERSON -->
       <div style="width: 200px;margin-left: 150px;" class="personFirstLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->name . ' ' . $dataToShow->patient->lastname; ?></p>
    </div>
    <div style="width: 200px;margin-left: 100px;" class="personSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->cuil ?></p>
    </div>
    <div style="width: 150px;margin-left: 400px;" class="personSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->phone!=''?$dataToShow->patient->phone:'No figura'  ?></p>
    </div>
    <div class="personThirdLine all">
        <p style="word-break: break-word;">
            <?php
            if ($dataToShow->patient->job =='Docente') : ?>
                <span style="margin-left:322px;"><?= $dataToShow->textForPDF(); ?></span>
            <?php else : ?>
            <span style="margin-left: 445px;"><?= $dataToShow->textForPDF(); ?></span>
            <?php endif; ?>
        </p>
    </div>
    <!-- END PERSON -->
     <!-- LICENSE REASON -->
     <div class="personFourthLine all">
        <?php
                if ($dataToShow->licence_reason==1) : ?>
                    <span style="margin-left:118px;"><?= $dataToShow->textForPDF(); ?></span>
                <?php elseif($dataToShow->licence_reason==2) : ?>
                    <span style="margin-left: 227px;"><?= $dataToShow->textForPDF(); ?></span>
                <?php elseif($dataToShow->licence_reason==3) : ?>
                    <span style="margin-left: 350px;"><?= $dataToShow->textForPDF(); ?></span>
                <?php elseif($dataToShow->licence_reason==4) : ?>
                    <span style="margin-left: 570px;"><?= $dataToShow->textForPDF(); ?></span>
                <?php elseif($dataToShow->licence_reason==5) : ?>
                    <span style="margin-left: 678px;"><?= $dataToShow->textForPDF(); ?></span>
        <?php endif; ?>
    </div>
    <!-- END LICENCE REASON -->
    <!-- GENERAL DICTAMEN -->
    <div class="resultsFirstLineDictamen all">
        <p style='margin-left:120px'><?= $dataToShow-> getSpeciality()?></p>
    </div>
    <div class="resultsSecondLineDictamen all">
        <p style='margin-left:160px'><?= $dataToShow-> getPathologyCode()?></p>
    </div>
    <!-- END GENERAL DICTAMEN -->
    <!-- DICTAMEN A NRLL-->
    <?php if($dataToShow->status==2):?> <!-- ausente 2-->
          <div class="resultsnrllFirstLine all">
                <span style="margin-left:130px;"><?= $dataToShow->textForPDF(); ?></span>
            </div>
    <?php  endif;?>
    <!-- END DICTAMEN A NRLL -->
    <!-- DICTAMEN LICENCIA -->
     <!-- DICTAMEN B Licencia -->
     <?php if(($dataToShow->status==3) && ($dataToShow->licence_reason == 1)):?> <!-- licencia negada 3 y 1-->
          <div class="resultsLicenseDeniedFirstLine all">
              <span style="margin-left:130px;"><?= $dataToShow->textForPDF(); ?></span> 
              <div class="resultsAcceptedFirstLine all"></div>
              <div class="resultsLicenseDeniedSecondLine all">
                 <span style="margin-left:130px;"><?= $dataToShow->textForPDF(); ?></span> 
              </div>    
            </div>
    <?php elseif(($dataToShow->status==4) && ($dataToShow->licence_reason ==1)):?> <!-- licencia otorgada cambiar a 4 y 1-->
        <div class="resultsLicenseDeniedFirstLine all">
            <span style="margin-left:130px;"><?= $dataToShow->textForPDF(); ?></span>
            <div class="resultsLicenceAcceptedFirstLine all">
                <span style="margin-left:130px;"><?= $dataToShow->textForPDF(); ?></span>
            </div>
            <div class="resultsLicenseAcceptedSecondLine all">
                <span style="margin-left:130px"><?= $dataToShow->recommendedDays; ?></span>
                <span style="margin-left:165px;"><?= $dataToShow->startLicense->day;?></span>
                <span style="margin-left: 15px;"><?= $dataToShow->startLicense->month;?></span>
                <span style="margin-left: 12px;"><?= $dataToShow->startLicense->year;?></span>
            </div>
        </div>
    <?php  endif;?>
    <!-- END DICTAMEN LICENCIA -->
    <!-- READECUACION DE TAREAS -->
    <?php if(($dataToShow->status==4) && ($dataToShow->licence_reason == 2)):?> <!--  cambio otorgado 4 2-->
        <div class="resultsChangeAcceptedFirstLine all">
            <span style="margin-left:117px;"><?= $dataToShow->textForPDF(); ?></span>
            <div class="resultsChangeAcceptedSecondLine all">
                <span style="margin-left:130px"><?= $dataToShow->recommendedDays; ?></span>
                <span style="margin-left: 170px;"><?= $dataToShow->startLicense->day;?></span>
                <span style="margin-left: 15px;"><?= $dataToShow->startLicense->month;?></span>
                <span style="margin-left: 12px;"><?= $dataToShow->startLicense->year;?></span>
            </div>
            <?php if(isset($dataToShow->interdiction)):?>
                <div class="resultsChangeAcceptedThirdLine all">
                    <span style="margin-left:140px"><?= $dataToShow->interdiction ?></span>
                </div>
            <?php endif;?>
        </div><!-- accepted change -->
    <?php elseif(($dataToShow->status==3) && ($dataToShow->licence_reason == 2)):?> <!-- cambio denegado 3 2 -->
        <div class="resultsChangeDeniedFirstLine all">
           <span style='margin-left:115px'><?= $dataToShow->textForPDF(); ?></span>
        </div>
    <?php endif;?>
    <!-- END READECUACION DE TAREAS -->
   <!-- PROVISORIOS POR RAZONES DE ENFERMEDAD -->
   <?php if(($dataToShow->status==4) && ($dataToShow->licence_reason == 4)) : ?>
        <div class="resultsProvisoryAcceptedFirstLine all">
            <span style='margin-left:115px'><?= $dataToShow->textForPDF(); ?></span>
            <span class="resultsProvisoryAcceptedSecondLine all">
                <span style="margin-left:130px"><?= $dataToShow->recommendedDays; ?></span>
                <span style="margin-left: 170px;"><?= $dataToShow->startLicense->day;?></span>
                <span style="margin-left: 15px;"><?= $dataToShow->startLicense->month;?></span>
                <span style="margin-left: 12px;"><?= $dataToShow->startLicense->year;?></span>
            </span>
            <?php if(isset($dataToShow->interdiction)):?>
                <span class="resultsProvisoryAcceptedThirdLine all"style="margin-left:140px"><?= $dataToShow->interdiction ?></span>
            <?php endif;?>
        </div>
        <?php elseif(($dataToShow->status==3) && ($dataToShow->licence_reason == 4)):?> <!-- cambio denegado 3 4 -->
        <div class="resultsProvisoryAcceptedForthLine all">
           <span style='margin-left:115px'><?= $dataToShow->textForPDF(); ?></span>
        </div>       
   <?php endif;?> 
   <!-- END PROVISORIOS POR RAZONES DE ENFERMEDAD --> 
   <!-- JUBILACION -->
   <?php if($dataToShow->retirement):?>
            <div class="resultsRetirementFirstLine all">
                <span style="margin-left:266px"><?= $dataToShow->textForPDF()?></span>
            </div>
        <?php else:?>
            <div class="resultsRetirementFirstLine all">
                <span style="margin-left:322px"><?= $dataToShow->textForPDF()?></span>
            </div>
        <?php endif;?>
   <!-- END JUBILACION -->   
</div><!-- end principal div -->
</body>
</html>
