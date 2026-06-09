<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />

<meta
    name="description"
    content="<?php echo $__env->yieldContent('meta_description', 'Sultan هو قالب بورتفوليو شخصي حديث واحترافي، مناسب لعرض الأعمال والسيرة الذاتية والمشاريع الشخصية بتصميم أنيق وحركات سلسة.'); ?>"
/>
<meta
    name="keywords"
    content="<?php echo $__env->yieldContent('meta_keywords', 'بورتفوليو شخصي, قالب بورتفوليو, سيرة ذاتية, قالب CV, موقع شخصي, قالب HTML عربي, أعمال, تصميم حديث'); ?>"
/>

<title><?php echo $__env->yieldContent('title', 'سلطان المسعري - خبير الاحجار الكريمة و مدير تنفيذي في صناعة المجوهرات'); ?></title>

<link rel="icon" href="<?php echo e(asset('assets/images/hero/fav.png')); ?>" type="image/x-icon" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" />
<style>
body.rtl {
  direction: rtl;
  text-align: right;
}

body.ltr {
  direction: ltr;
  text-align: left;
  line-height: 1.6;
}

body.ltr [data-lang="en"] {
  display: block;
  margin-bottom: 1em;
}

body.rtl [data-lang="ar"] {
  display: block;
}
</style>
<?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/partials/site-head.blade.php ENDPATH**/ ?>