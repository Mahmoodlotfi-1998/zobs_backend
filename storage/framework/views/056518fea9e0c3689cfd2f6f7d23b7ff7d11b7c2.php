<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> پرداختی پیس وان</title>

    <link href="<?php echo e(asset('include/css/main.css')); ?>" rel="stylesheet">

</head>

<body class="body_pay">
    <div class="color_opacity" style="float: right;width: 100%;">


        <div class="content">

            <?php echo $text; ?>


            <div class="footer">
                <div style="margin-right: -12%; width: 100%; height: 50%;direction: rtl;  color: white;"><h4>تمامی حقوق برای کارا سرویس محفوظ است.</h4></div>
                <div class="phone">
                    <div class="support">شماره تماس پشتیبانی:</div>
                    <div class="support2">
                        <?php echo $phone; ?>

                    </div>
                </div>

            </div>
        </div>

    </div>
</body>
</html>
<?php /**PATH /home/adverserviceir/zoobs/resources/views/payment.blade.php ENDPATH**/ ?>