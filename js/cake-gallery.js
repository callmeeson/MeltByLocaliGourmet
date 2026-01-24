/**
 * Cake Gallery Scroll Animation
 * Images zoom in by default and zoom out as they come into view
 */

document.addEventListener('DOMContentLoaded', function() {
	const galleryItems = document.querySelectorAll('.gallery-item');

	if (!galleryItems.length) return;

	// Start all images zoomed in
	galleryItems.forEach(item => {
		const image = item.querySelector('.gallery-item-image');
		if (image) {
			image.style.transform = 'scale(1.3)';
		}
	});

	// Animate zoom out as they come into view
	function updateGalleryZoom() {
		galleryItems.forEach(item => {
			const rect = item.getBoundingClientRect();
			const windowHeight = window.innerHeight;
			const itemCenter = rect.top + rect.height / 2;
			const windowCenter = windowHeight / 2;
			
			// Calculate distance from center of screen
			const distance = Math.abs(itemCenter - windowCenter);
			const maxDistance = windowHeight;
			
			// Calculate zoom scale (1.3 = zoomed in, 1 = normal)
			// The closer to center, the more zoomed out it becomes
			const progress = Math.max(0, 1 - (distance / maxDistance));
			const zoomScale = 1.3 - (progress * 0.3); // Goes from 1.3 to 1.0
			
			// Apply the zoom transform
			const image = item.querySelector('.gallery-item-image');
			if (image) {
				image.style.transform = `scale(${zoomScale})`;
			}
		});
	}

	// Update on scroll
	window.addEventListener('scroll', updateGalleryZoom, { passive: true });
	
	// Initial update
	updateGalleryZoom();

	// Update on window resize
	window.addEventListener('resize', updateGalleryZoom);

	// Optional: Add click handler for lightbox/modal if needed
	galleryItems.forEach(item => {
		item.addEventListener('click', function() {
			const image = this.querySelector('.gallery-item-image');
			if (image) {
				console.log('Gallery item clicked:', image.alt);
			}
		});
	});

	// Add hover effect tracking
	galleryItems.forEach(item => {
		item.addEventListener('mouseenter', function() {
			this.style.zIndex = '10';
		});

		item.addEventListener('mouseleave', function() {
			this.style.zIndex = '1';
		});
	});
});
