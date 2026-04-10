<div class="container mt-4">
<div class="row">
<?php foreach($products as $p): ?>
<div class="col-md-3 mb-4">
<div class="card shadow-sm product-card">
<img src="<?= $p->image ?>" class="card-img-top">
<div class="card-body text-center">
<h6><?= htmlspecialchars($p->name) ?></h6>
<p>₹<?= $p->price ?></p>
<button class="btn btn-primary addCart" data-id="<?= $p->id ?>">
Add to Cart
</button>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
<style>
.product-card:hover {
    transform: scale(1.05);
    transition: .3s;
}
</style>
<script>
$('.addCart').click(function(){
    let id = $(this).data('id');
    $.post("<?= base_url('cart/add') ?>",{id:id},function(res){
        let r = JSON.parse(res);
        if(r.status){
            $('#cartCount').text(r.count);
            Swal.fire('Added!','','success');
        }
    });
});
</script>