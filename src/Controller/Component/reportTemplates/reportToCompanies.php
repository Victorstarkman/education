<style>
    .all {
        position: absolute;
        text-transform: uppercase;
        font-weight: bold;
        word-break: break-all;
    }

    .personFirstLine {
        top: 315px;
    }

    .personSecondLine {
        top:355px;
    }

    .personThirdLine {
        top:392px;
    }
    .personFourthLine {
        top:430px;
    }

    .licenseType {
        top: 542px;
    }

    .licenseType .type-1 {
        margin-left: 126px;
    }
    .licenseType .type-2 {
        margin-left: 271px;
    }
    .licenseType .type-3 {
        margin-left: 536px;
    }

    .resultsFirstLine {
        top: 700px
    }

    .status-<?= \App\Model\Table\ReportsTable::NRLL; ?> {
        margin-left: 153px;
    }

    .status-<?= \App\Model\Table\ReportsTable::DENIED; ?> {
        margin-left: 419px;
    }

    .status-<?= \App\Model\Table\ReportsTable::GRANTED; ?> {
        margin-left: 665px;
    }

    .resultsSecondLine {
        top: 732px;
        margin-left: 280px;
        width: 450px;
    }

    .resultThirdLine {
        top: 775px;
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
    <img src="http://local.ausentismo.com:8886/img/dienst-form.jpg" style="max-width: 792px"/>
    <!-- PERSON -->
    <div style="width: 200px;margin-left: 200px;" class="personFirstLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->name . ' ' . $dataToShow->patient->lastname; ?></p>
    </div>
    <div style="width: 169px;margin-left: 457px;" class="personFirstLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->document;  ?></p>
    </div>
    <div style="width: 40px;margin-left: 694px;" class="personFirstLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->age;  ?></p>
    </div>
    <div style="width: 80px;margin-left: 111px;" class="personSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->created->format('d/m/Y'); ?></p>
    </div>
    <div style="width: 80px;margin-left: 241px;" class="personSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->created->format('H:i'); ?></p>
    </div>
    <div style="width: 265px;margin-left: 471px;" class="personSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->job;  ?></p>
    </div>
    <div style="width: 156px;margin-left: 134px;" class="personThirdLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->company->name;  ?></p>
    </div>
    <div style="width: 150px;margin-left: 320px;" class="personThirdLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->phone;  ?></p>
    </div>
    <div style="width: 236px;margin-left: 515px;" class="personThirdLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->email;  ?></p>
    </div>
    <div style="width: 266px;margin-left: 138px;" class="personFourthLine all">
        <p style="word-break: break-word;"><?= $dataToShow->patient->address;  ?></p>
    </div>
    <div style="width: 256px;margin-left: 481px;" class="personFourthLine all">
        <p style="word-break: break-word;"><?= 'LOCALIDAD'; ?></p>
    </div>
    <!-- END PERSON -->
    <!-- LICENSE TYPE -->
    <div style="width: 256px;" class="licenseType all">
        <p style="word-break: break-word;" class="type-<?= $dataToShow->type; ?>"><?= $dataToShow->textForPDF(); ?></p>
    </div>
    <div style="width: 193px;top:590px;margin-left:551px;" class=" all">
        <p style="word-break: break-word;"><?= $dataToShow->askedDays; ?></p>
    </div>
    <!-- END LICENSE TYPE -->
    <!-- RESULT -->
    <div style="width: 256px;" class="resultsFirstLine all">
        <p style="word-break: break-word;" class="status-<?= $dataToShow->status; ?>">X</p>
    </div>
    <div class="resultsSecondLine all">
        <p style="word-break: break-word;"><?= $dataToShow->cie10; ?></p>
    </div>
    <div class="resultThirdLine all" style="width: 262px; margin-left: 274px;">
        <p style="word-break: break-word;"><?= $dataToShow->recommendedDays; ?></p>
    </div>
    <div class="resultThirdLine all">
        <p style="word-break: break-word;">
            <span style="margin-left: 650px;"><?= $dataToShow->startLicense->day;?></span>
            <span style="margin-left: 15px;"><?= $dataToShow->startLicense->month;?></span>
            <span style="margin-left: 12px;"><?= $dataToShow->startLicense->year;?></span>
        </p>
    </div>
    <div class="resultFourthLine all">
        <p style="word-break: break-word;"><?= $dataToShow->observations; ?></p>
    </div>
    <!-- END RESULT -->
    <!-- SIGNATURE -->
    <div class="signature all">
        <img src="<?= $dataToShow->doctor->signature; ?>" style="position: absolute;top: 80px;left: 230px;">
        <div style="position: absolute;top: 100px; margin: 0;left: 350px;font-size: 11px;text-align: center;">
            <p style="margin: 0;"><?= strtoupper($dataToShow->doctor->name . ' ' . $dataToShow->doctor->lastname);?> </p>
            <p style="margin: 0;">MÉDICO</p>
            <p style="margin: 0;">M.P: <?= $dataToShow->doctor->license; ?>  - M.N. <?= $dataToShow->doctor->licenseNational; ?></p>
        </div>
    </div>
    <!-- END SIGNATURE -->
</div>
</body>
</html>
