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
        top: 210px;
    }

    .personSecondLine {
        top:355px;
    }

    .personThirdLine {
        top:287px;
    }
    .personFourthLine {
        top:368px;
    }

    .licenseType {
        top: 580px;
    }

    .licenseType .type-1 {
        margin-left: 107px;
    }
    .licenseType .type-2 {
        margin-left: 455px;
    }
    .licenseType .type-3 {
        margin-left: 688px;
    }

    .resultsFirstLine {
        top: 678px
    }

    .status-<?= \App\Model\Table\ReportsTable::NRLL; ?> {
        margin-left: 145px;
    }

    .status-<?= \App\Model\Table\ReportsTable::DENIED; ?> {
        margin-left: 416px;
    }

    .status-<?= \App\Model\Table\ReportsTable::GRANTED; ?> {
        margin-left: 688px;
    }

    .resultsSecondLine {
        top: 720px;
        margin-left: 200px;
        width: 450px;
    }

    .resultThirdLine {
        top: 755px;
    }

    .resultFourthLine {
        top: 815px;
        margin-left: 66px;
        width: 663px;
        text-indent: 107px;

    }

    .signature {
        top: 880px;
        left: 50px;
    }
</style>
<html>
<head><title>Export</title></head>
<body>
<div>
    <img src="<?= WWW_ROOT;?>img/formulario_ambulatorio.jpg" style="max-width: 792px"/>
    <!-- PERSON -->
    <div style="width: 200px;margin-left: 200px;" class="personFirstLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->name . ' ' . $dataToShow->patient->lastname; ?></p>
    </div>
    <div style="width: 169px;margin-left: 80px;" class="personThirdLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->cuil;  ?></p>
    </div>
    <div style="width: 150px;margin-left: 375px;" class="personThirdLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->phone;  ?></p>
    </div>
    <div style="width: 266px;margin-left: 160px;" class="personFourthLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->address;  ?></p>
    </div>
    <!-- END PERSON -->
    <!-- LICENSE TYPE -->
    <div style="width: 256px;" class="licenseType all">
        <p style="word-break: break-word;" class="type-<?= $dataToShow->type; ?>"><?= $dataToShow->textForPDF(); ?></p>
    </div>
    <!-- END LICENSE TYPE -->
    <!-- RESULT -->
    <div style="width: 256px;" class="resultsFirstLine all">
        <p style="word-break: break-word;" class="status-<?= $dataToShow->status; ?>">X</p>
    </div>
    <div class="resultsSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->getPathologyCode(); ?></p>
    </div>
    <div class="resultThirdLine all" style="width: 262px; margin-left: 180px;">
        <p style="word-break: break-word;"><?= $dataToShow->recommendedDays; ?></p>
    </div>
    <div class="resultThirdLine all">
        <p style="word-break: break-word;">
            <?php
            if (is_null($dataToShow->startLicense)) : ?>
                <span style="margin-left:440px;">-</span>
                <span style="margin-left: 28px;">-</span>
                <span style="margin-left: 20px;">-</span>
            <?php else : ?>
            <span style="margin-left: 440px;"><?= $dataToShow->startLicense->day;?></span>
            <span style="margin-left: 15px;"><?= $dataToShow->startLicense->month;?></span>
            <span style="margin-left: 12px;"><?= $dataToShow->startLicense->year;?></span>
            <?php endif; ?>
        </p>
    </div>
    <div class="resultFourthLine all">
        <p style="word-break: break-word;"><?= $dataToShow->observations; ?></p>
    </div>
    <!-- END RESULT -->
    <!-- SIGNATURE -->
    <!-- END SIGNATURE -->
</div>
</body>
</html>
