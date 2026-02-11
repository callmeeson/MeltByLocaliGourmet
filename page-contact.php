<?php

/**
 * Template Name: Contact
 * Description: Custom contact page template for Melt by Locali Gourmet.
 *
 * @package Melt_Custom
 */

get_header();
?>

<div class="contact-page-wrapper">
	<section class="contact-hero fade-in-section">
		<div class="contact-hero__content">
			<span class="contact-hero__eyebrow">Get In Touch</span>
			<h1 class="contact-hero__title">We'd Love to Hear From You</h1>
			<p class="contact-hero__subtitle">Have a question about our cakes or need a custom order? Our team is here to help.</p>
		</div>
	</section>

	<section class="section contact-section fade-in-section">
		<div class="section-container">
			<div class="contact-layout">
				<!-- Contact Info Sidebar -->
				<div class="contact-sidebar">
					<div class="contact-card">
						<h2 class="contact-card__title">Contact Information</h2>
						<p class="contact-card__desc">Reach out to us directly or visit our boutique.</p>

						<div class="contact-info-list">
							<div class="contact-info-item">
								<div class="contact-icon">
									<i data-lucide="phone"></i>
								</div>
								<div class="contact-details">
									<span class="contact-label">Phone</span>
									<a href="tel:+97141234567" class="contact-value">+971 4 123 4567</a>
								</div>
							</div>

							<div class="contact-info-item">
								<div class="contact-icon">
									<i data-lucide="mail"></i>
								</div>
								<div class="contact-details">
									<span class="contact-label">Email</span>
									<a href="mailto:hello@meltbylg.com" class="contact-value">hello@meltbylg.com</a>
								</div>
							</div>

							<div class="contact-info-item">
								<div class="contact-icon">
									<i data-lucide="map-pin"></i>
								</div>
								<div class="contact-details">
									<span class="contact-label">Location</span>
									<span class="contact-value">Dubai Mall, Downtown Dubai<br>Fashion Avenue, Level 1</span>
								</div>
							</div>
						</div>
					</div>

					<div class="contact-card hours-card">
						<h3 class="contact-card__title">Opening Hours</h3>
						<div class="hours-list">
							<div class="hours-row">
								<span class="day">Monday - Friday</span>
								<span class="time">10:00 AM - 10:00 PM</span>
							</div>
							<div class="hours-row">
								<span class="day">Saturday - Sunday</span>
								<span class="time">10:00 AM - 12:00 AM</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Contact Form -->
				<div class="contact-form-container">
					<div class="form-header">
						<h2 class="form-title">Send us a Message</h2>
						<p class="form-subtitle">Fill out the form below and we'll get back to you within 24 hours.</p>
					</div>

					<form class="contact-form" action="#" method="post">
						<div class="form-grid">
							<div class="form-group">
								<label for="contact-name">Full Name <span class="required">*</span></label>
								<input type="text" id="contact-name" name="contact-name" placeholder="John Doe" required>
							</div>

							<div class="form-group">
								<label for="contact-email">Email Address <span class="required">*</span></label>
								<input type="email" id="contact-email" name="contact-email" placeholder="john@example.com" required>
							</div>

							<div class="form-group full-width">
								<label for="contact-subject">Subject</label>
								<select id="contact-subject" name="contact-subject">
									<option value="general">General Inquiry</option>
									<option value="order">Order Status</option>
									<option value="custom">Custom Cake Request</option>
									<option value="feedback">Feedback</option>
								</select>
							</div>

							<div class="form-group full-width">
								<label for="contact-message">Message <span class="required">*</span></label>
								<textarea id="contact-message" name="contact-message" rows="6" placeholder="How can we help you?" required></textarea>
							</div>
						</div>

						<button type="submit" class="submit-btn">
							<span>Send Message</span>
							<i data-lucide="send"></i>
						</button>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<style>
	/* Page Layout */
	.contact-page-wrapper {
		background-color: var(--background);
		padding-top: 80px;
		/* Header clearance */
	}

	/* Hero Section */
	.contact-hero {
		padding: 4rem 1.5rem 3rem;
		text-align: center;
		background: linear-gradient(to bottom, rgba(248, 248, 248, 0.5), rgba(255, 255, 255, 0));
	}

	.contact-hero__content {
		max-width: 800px;
		margin: 0 auto;
	}

	.contact-hero__eyebrow {
		display: block;
		font-family: var(--font-body);
		font-size: 0.85rem;
		text-transform: uppercase;
		letter-spacing: 0.15em;
		color: var(--primary);
		margin-bottom: 1rem;
		font-weight: 600;
	}

	.contact-hero__title {
		font-family: var(--font-serif);
		font-size: clamp(2.5rem, 5vw, 3.5rem);
		color: var(--foreground);
		margin-bottom: 1rem;
		line-height: 1.1;
	}

	.contact-hero__subtitle {
		font-family: var(--font-body);
		font-size: 1.1rem;
		color: var(--muted-foreground);
		line-height: 1.6;
		max-width: 600px;
		margin: 0 auto;
	}

	/* Main Layout */
	.contact-layout {
		display: grid;
		grid-template-columns: 1fr;
		gap: 3rem;
		max-width: 1200px;
		margin: 0 auto;
	}

	@media (min-width: 900px) {
		.contact-layout {
			grid-template-columns: 1fr 1.5fr;
			gap: 4rem;
			align-items: start;
		}
	}

	/* Sidebar Cards */
	.contact-sidebar {
		display: flex;
		flex-direction: column;
		gap: 2rem;
	}

	.contact-card {
		background: var(--card);
		border: 1px solid var(--border);
		border-radius: 12px;
		padding: 2rem;
		box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
	}

	.contact-card__title {
		font-family: var(--font-serif);
		font-size: 1.5rem;
		margin-bottom: 0.5rem;
		color: var(--foreground);
	}

	.contact-card__desc {
		font-family: var(--font-body);
		font-size: 0.95rem;
		color: var(--muted-foreground);
		margin-bottom: 2rem;
	}

	.contact-info-list {
		display: flex;
		flex-direction: column;
		gap: 1.5rem;
	}

	.contact-info-item {
		display: flex;
		align-items: flex-start;
		gap: 1rem;
	}

	.contact-icon {
		width: 40px;
		height: 40px;
		background-color: var(--secondary);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		color: var(--primary);
		flex-shrink: 0;
	}

	.contact-icon i {
		width: 20px;
		height: 20px;
	}

	.contact-details {
		display: flex;
		flex-direction: column;
	}

	.contact-label {
		font-family: var(--font-body);
		font-size: 0.75rem;
		text-transform: uppercase;
		letter-spacing: 0.05em;
		color: var(--muted-foreground);
		margin-bottom: 0.25rem;
	}

	.contact-value {
		font-family: var(--font-body);
		font-size: 1rem;
		color: var(--foreground);
		font-weight: 500;
		line-height: 1.4;
	}

	a.contact-value:hover {
		color: var(--primary);
	}

	/* Hours */
	.hours-list {
		display: flex;
		flex-direction: column;
		gap: 1rem;
	}

	.hours-row {
		display: flex;
		justify-content: space-between;
		font-family: var(--font-body);
		font-size: 0.95rem;
		padding-bottom: 0.75rem;
		border-bottom: 1px dashed var(--border);
	}

	.hours-row:last-child {
		border-bottom: none;
		padding-bottom: 0;
	}

	.hours-row .day {
		color: var(--muted-foreground);
	}

	.hours-row .time {
		color: var(--foreground);
		font-weight: 500;
	}

	/* Contact Form */
	.contact-form-container {
		background: var(--card);
		border: 1px solid var(--border);
		border-radius: 16px;
		padding: 2.5rem;
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
	}

	.form-header {
		margin-bottom: 2rem;
	}

	.form-title {
		font-family: var(--font-serif);
		font-size: 1.75rem;
		margin-bottom: 0.5rem;
		color: var(--foreground);
	}

	.form-subtitle {
		font-family: var(--font-body);
		font-size: 1rem;
		color: var(--muted-foreground);
	}

	.form-grid {
		display: grid;
		grid-template-columns: 1fr;
		gap: 1.5rem;
		margin-bottom: 2rem;
	}

	@media (min-width: 640px) {
		.form-grid {
			grid-template-columns: 1fr 1fr;
		}

		.form-group.full-width {
			grid-column: span 2;
		}
	}

	.form-group {
		display: flex;
		flex-direction: column;
		gap: 0.5rem;
	}

	.form-group label {
		font-family: var(--font-body);
		font-size: 0.9rem;
		font-weight: 500;
		color: var(--foreground);
	}

	.form-group label .required {
		color: var(--destructive);
	}

	.form-group input,
	.form-group select,
	.form-group textarea {
		padding: 0.75rem 1rem;
		border: 1px solid var(--border);
		border-radius: 8px;
		font-family: var(--font-body);
		font-size: 1rem;
		background-color: var(--input-background);
		transition: all 0.2s ease;
	}

	.form-group input:focus,
	.form-group select:focus,
	.form-group textarea:focus {
		outline: none;
		border-color: var(--primary);
		box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
	}

	.submit-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 0.75rem;
		background-color: var(--primary);
		color: white;
		padding: 1rem 2rem;
		border: none;
		border-radius: 8px;
		font-family: var(--font-body);
		font-size: 1rem;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.3s ease;
		width: 100%;
	}

	.submit-btn:hover {
		background-color: var(--accent);
		transform: translateY(-2px);
		box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
	}

	.submit-btn i {
		width: 18px;
		height: 18px;
	}
</style>

<?php get_footer(); ?>