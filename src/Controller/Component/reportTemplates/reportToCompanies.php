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
    .modeFirstLine {
        top: 145px;
    }
    .personFirstLine {
        top: 210px;
    }

    .personSecondLine {
        top:355px;
    }

    .personThirdLine {
        top:277px;
    }
    .personFourthLine {
        top:315px;
    }

    .licenseType {
        top: 515px;
    }


    .resultsFirstLine {
        top: 585px
    }

    .resultsSecondLine {
        top: 620px;
        margin-left:130px;
        width: 450px;
    }

    .resultThirdLine {
        top: 650px;
    }

    .resultFourthLine {
        top: 685px;
       
    }
    .resultFifthLine{
        top:735px
    }
    .resultSixLine{
        top:750px
    }

</style>
<?php  //debug($dataToShow); die();?>

<html>
<head><title>Export</title></head>
<body>
<div>
    <img src="<?= WWW_ROOT;?>img/form_licencia.jpg" style="max-width: 792px"/>
    <!-- PERSON -->
    <div  class="modeFirstLine all"> <!-- 1 auditoria 2 visita domic 3 especializadas 4 juntas 5 virtuales -->
        <?php if($dataToShow->mode_id == 1):?>
            <p style="margin-left:128px" ><?= $dataToShow->textForPDF(); ?></p>
        <?php elseif($dataToShow->mode_id== 5):?>
            <p style="margin-left:295px"><?= $dataToShow->textForPDF(); ?></p>
        <?php elseif($dataToShow->mode_id== 3):?>
            <p style="margin-left:463px" ><?= $dataToShow->textForPDF(); ?></p>
        <?php elseif($dataToShow->mode_id== 2):?>
            <p style="margin-left:630px" ><?= $dataToShow->textForPDF(); ?></p>
        <?php endif;?>
    </div>
    <div style="width: 400px;margin-left: 200px;" class="personFirstLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->name . ' ' . $dataToShow->patient->lastname; ?></p>
    </div>
    <div style="width: 169px;margin-left: 80px;" class="personThirdLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->cuil;  ?></p>
    </div>
    <div style="width: 150px;margin-left: 405px;" class="personThirdLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->phone;  ?></p>
    </div>
    <?php  if ($dataToShow->patient->job !=="Auxiliar"):  ?> 
        <div  class="personFourthLine all">
            <p style="margin-left:110px"><?= $dataToShow->textForPDF()?></p>
        </div>
    <?php else:?>
        <div  class="personFourthLine all">
            <p style="margin-left:362px"><?= $dataToShow->textForPDF()?></p>
        </div>
    <?php endif;?>
    <!-- END PERSON -->
    <!-- LICENSE TYPE -->
    <div style="width: 256px;" class="licenseType all">
        <?php if($dataToShow->type== 1):?>
            <p style="margin-left:110px" ><?= $dataToShow->textForPDF(); ?></p>
        <?php elseif($dataToShow->type== 3):?>
            <p style="margin-left:360px"><?= $dataToShow->textForPDF(); ?></p>
        <?php elseif($dataToShow->type== 2):?>
            <p style="margin-left:630px" ><?= $dataToShow->textForPDF(); ?></p>
        <?php endif;?>
    </div>
    <!-- END LICENSE TYPE -->
    <!-- RESULT -->
    <div style="width: 256px;" class="resultsFirstLine all">
        <?php if($dataToShow->status== 4):?>
            <p style="margin-left:128px" ><?= $dataToShow->textForPDF(); ?></p>
        <?php elseif($dataToShow->status== 2):?>
            <p style="margin-left:360px" ><?= $dataToShow->textForPDF(); ?></p>
        <?php elseif($dataToShow->status== 3):?>
            <p style="margin-left:630px" ><?= $dataToShow->textForPDF(); ?></p>
        <?php endif;?>
    </div>
    <div class="resultsSecondLine all">
        <p class='Specialties' style="word-break: break-word"><?= $dataToShow->getSpeciality(); ?></p>
    </div>
    <div class="resultThirdLine all" style="width: 262px; margin-left: 180px;">
        <p style="word-break: break-word;"><?= $dataToShow->getPathologyCode(); ?></p>
    </div>
    <div class="resultFourthLine all">
        <p style='margin-left:110px'><?= $dataToShow->recommendedDays?>
        <?php if(!isset($dataToShow->startLicense)):?>
            <span style='margin-left:250px'>-</span>
            <span style='margin-left:20px'>-</span>
            <span style='margin-left:30px'>-</span>
        <?php else:?>
            <span style='margin-left:270px'><?= $dataToShow->startLicense->day;?></span>
            <span style='margin-left:20px'><?= $dataToShow->startLicense->month;?></span>
            <span style='margin-left:30px'><?= $dataToShow->startLicense->year;?></span>
        <?php endif;?>
        </p>
           
    </div>
    <?php if(!is_null($dataToShow->eval_council)):?>
        <div class="resultFifthLine all">
        <span style='margin-left: 310px;'><?= $dataToShow->textForPDF(); ?></span>
        </div>
    <?php endif;?>
    <?php if(isset($dataToShow->observations)) :?>
        <p class='resultSixLine all' style='word-break: break-word; width:750px; margin-left:100px'>
            <?= $dataToShow->observations?>
        </p>
    <?php endif;?>
    <!-- END RESULT -->
    <!-- SIGNATURE -->
    <!-- END SIGNATURE -->
</div>
</body>
</html>
<?php
            