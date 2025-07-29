<section class="container py-12">
  <h2 class="header-title">Our Models</h2>
  <div class="models-grid">
    @foreach($models as $model)
      <div class="card model-card">
        <img src="{{ $model->image }}" alt="{{ $model->name }}">
        <h3 class="model-card-title">{{ $model->name }}</h3>
        <p class="model-card-desc">{{ $model->description }}</p>
      </div>
    @endforeach
  </div>
</section>

