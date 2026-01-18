<?php
/**
 * Template Name: Contact
 * Description: Custom contact page template for Melt by Locali Gourmet.
 *
 * @package Melt_Custom
 */

get_header();
?>

<section class="contact-hero">
	<div class="contact-hero__content">
		<p class="contact-hero__eyebrow">Contact Us</p>
		<h1 class="contact-hero__title">Have a question or ready to order? We'd love to hear from you.</h1>
	</div>
	<div class="contact-hero__glow"></div>
</section>

<section class="section contact-section">
	<div class="section-container contact-layout">
		<div class="contact-details">
			<div class="contact-card">
				<h2 class="contact-card__title">Get in Touch</h2>
				<p class="contact-card__subtitle">We reply within one business day.</p>
				<div class="contact-info-list">
					<div class="contact-info-item">
						<p class="contact-info-item__label">Phone</p>
						<p class="contact-info-item__value">+971 4 123 4567</p>
					</div>
					<div class="contact-info-item">
						<p class="contact-info-item__label">Email</p>
						<p class="contact-info-item__value">hello@meltbylg.com</p>
					</div>
					<div class="contact-info-item">
						<p class="contact-info-item__label">Location</p>
						<p class="contact-info-item__value">Dubai Mall, Downtown Dubai</p>
					</div>
				</div>
			</div>

			<div class="contact-card contact-card--hours">
				<h3 class="contact-card__title">Opening Hours</h3>
				<div class="contact-hours">
					<div class="contact-hours__row">
						<span class="contact-hours__day">Monday - Friday</span>
						<span class="contact-hours__time">9:00 AM - 10:00 PM</span>
					</div>
					<div class="contact-hours__row">
						<span class="contact-hours__day">Saturday - Sunday</span>
						<span class="contact-hours__time">10:00 AM - 11:00 PM</span>
					</div>
				</div>
			</div>
		</div>

		<div class="contact-form-card">
			<h2 class="contact-card__title">Send a Message</h2>
			<p class="contact-card__subtitle">Tell us about your inquiry and we will get back to you.</p>
			<form class="contact-form" action="#" method="post">
				<div class="contact-form__grid">
					<div class="contact-form__field">
						<label for="contact-name">Full Name <span aria-hidden="true">*</span></label>
						<input id="contact-name" name="contact-name" type="text" placeholder="Your name" required>
					</div>
					<div class="contact-form__field">
						<label for="contact-email">Email Address <span aria-hidden="true">*</span></label>
						<input id="contact-email" name="contact-email" type="email" placeholder="your.email@example.com" required>
					</div>
					<div class="contact-form__field">
						<label for="contact-phone">Phone Number</label>
						<input id="contact-phone" name="contact-phone" type="tel" placeholder="+971 XX XXX XXXX">
					</div>
					<div class="contact-form__field contact-form__field--full">
						<label for="contact-message">Message <span aria-hidden="true">*</span></label>
						<textarea id="contact-message" name="contact-message" rows="5" placeholder="Tell us about your inquiry..." required></textarea>
					</div>
				</div>
				<button class="contact-form__submit" type="submit">
					Send Message
				</button>
			</form>
		</div>
	</div>
</section>

<?php get_footer(); ?>
