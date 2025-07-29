<section class="container py-12">
  <h2 class="header-title">Our Services</h2>
  <div class="services-grid">
    @foreach($services as $service)
      <div class="card service-card">
        <div class="service-card-icon">
          <i class="{{ $service->icon }}"></i>
        </div>
        <h3 class="service-card-title">{{ $service->title }}</h3>
        <p class="service-card-desc">{{ $service->description }}</p>
      </div>
    @endforeach
  </div>
</section>
