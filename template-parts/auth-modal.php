<?php
/**
 * Authentication Modal Template Part
 * 
 * Login and Registration Modal
 */
?>

<!-- Auth Modal -->
<div id="authModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(8px); z-index: 10000; padding: 1rem; align-items: center; justify-content: center;">
	<div style="background: linear-gradient(135deg, #FFF8F0 0%, #FFFFFF 100%); border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); max-width: 450px; width: 100%; max-height: 90vh; overflow-y: auto; animation: modalSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
		
		<!-- Header -->
		<div id="authModalHeader" style="position: sticky; top: 0; background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%); padding: 1.5rem 2rem; border-radius: 16px 16px 0 0; z-index: 10;">
			<div style="display: flex; align-items: center; justify-content: space-between;">
				<div>
					<h2 id="authModalTitle" style="color: white; font-family: var(--font-serif); font-size: 1.75rem; margin: 0; font-weight: 600;">
						Welcome Back
					</h2>
					<p id="authModalSubtitle" style="color: rgba(255, 255, 255, 0.9); font-family: var(--font-body); margin: 0.5rem 0 0; font-size: 0.875rem;">
						Sign in to continue your order
					</p>
				</div>
				<button onclick="closeAuthModal()" style="color: white; background: rgba(255, 255, 255, 0.2); border: none; border-radius: 50%; padding: 0.5rem; cursor: pointer; transition: background 0.3s ease; width: 2.5rem; height: 2.5rem; display: flex; align-items: center; justify-content: center;"
					onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'"
					onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
					<i data-lucide="x" style="width: 1.25rem; height: 1.25rem;"></i>
				</button>
			</div>
		</div>

		<!-- Form -->
		<form id="authForm" method="post" style="padding: 2rem; display: flex; flex-direction: column; gap: 1.25rem;">
			<?php wp_nonce_field( 'melt_auth_action', 'melt_auth_nonce' ); ?>
			
			<!-- Name (Sign Up Only) -->
			<div id="nameField" style="display: none;">
				<label style="display: block; color: var(--primary); font-family: var(--font-body); font-weight: 500; margin-bottom: 0.5rem; font-size: 0.875rem;">
					Full Name
				</label>
				<div style="position: relative;">
					<i data-lucide="user" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--primary);"></i>
					<input
						type="text"
						id="authName"
						name="name"
						placeholder="Enter your full name"
						style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 2px solid rgba(184, 134, 11, 0.3); border-radius: 8px; background: rgba(255, 255, 255, 0.5); color: var(--foreground); font-family: var(--font-body); transition: all 0.3s ease;"
						onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
						onblur="this.style.borderColor='rgba(184, 134, 11, 0.3)'; this.style.boxShadow='none'">
				</div>
				<span id="nameError" class="auth-error" style="display: none; color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;"></span>
			</div>

			<!-- Email -->
			<div>
				<label style="display: block; color: var(--primary); font-family: var(--font-body); font-weight: 500; margin-bottom: 0.5rem; font-size: 0.875rem;">
					Email Address
				</label>
				<div style="position: relative;">
					<i data-lucide="mail" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--primary);"></i>
					<input
						type="email"
						id="authEmail"
						name="email"
						placeholder="your.email@example.com"
						required
						style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 2px solid rgba(184, 134, 11, 0.3); border-radius: 8px; background: rgba(255, 255, 255, 0.5); color: var(--foreground); font-family: var(--font-body); transition: all 0.3s ease;"
						onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
						onblur="this.style.borderColor='rgba(184, 134, 11, 0.3)'; this.style.boxShadow='none'">
				</div>
				<span id="emailError" class="auth-error" style="display: none; color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;"></span>
			</div>

			<!-- Phone (Sign Up Only) -->
			<div id="phoneField" style="display: none;">
				<label style="display: block; color: var(--primary); font-family: var(--font-body); font-weight: 500; margin-bottom: 0.5rem; font-size: 0.875rem;">
					Phone Number
				</label>
				<div style="position: relative;">
					<i data-lucide="phone" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--primary);"></i>
					<input
						type="tel"
						id="authPhone"
						name="phone"
						placeholder="+971 XX XXX XXXX"
						style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 2px solid rgba(184, 134, 11, 0.3); border-radius: 8px; background: rgba(255, 255, 255, 0.5); color: var(--foreground); font-family: var(--font-body); transition: all 0.3s ease;"
						onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
						onblur="this.style.borderColor='rgba(184, 134, 11, 0.3)'; this.style.boxShadow='none'">
				</div>
				<span id="phoneError" class="auth-error" style="display: none; color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;"></span>
			</div>

			<!-- Password -->
			<div>
				<label style="display: block; color: var(--primary); font-family: var(--font-body); font-weight: 500; margin-bottom: 0.5rem; font-size: 0.875rem;">
					Password
				</label>
				<div style="position: relative;">
					<i data-lucide="lock" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--primary);"></i>
					<input
						type="password"
						id="authPassword"
						name="password"
						placeholder="Enter your password"
						required
						style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 2px solid rgba(184, 134, 11, 0.3); border-radius: 8px; background: rgba(255, 255, 255, 0.5); color: var(--foreground); font-family: var(--font-body); transition: all 0.3s ease;"
						onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
						onblur="this.style.borderColor='rgba(184, 134, 11, 0.3)'; this.style.boxShadow='none'">
				</div>
				<span id="passwordError" class="auth-error" style="display: none; color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;"></span>
				
				<!-- Password Strength Meter -->
				<div id="passwordStrengthMeter" style="display: none; margin-top: 0.75rem;">
					<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
						<span style="font-size: 0.75rem; color: var(--muted-foreground); font-family: var(--font-body);">Password Strength:</span>
						<span class="strength-text" style="font-size: 0.75rem; font-weight: 600; font-family: var(--font-body);"></span>
					</div>
					<div style="height: 6px; background: var(--secondary); border-radius: 3px; overflow: hidden;">
						<div class="strength-bar-fill" style="height: 100%; width: 0%; transition: all 0.3s ease; border-radius: 3px;"></div>
					</div>
					<div class="password-requirements"></div>
				</div>
			</div>

			<!-- Submit Button -->
			<button
				type="submit"
				id="authSubmitBtn"
				style="width: 100%; padding: 0.875rem 1.5rem; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); color: white; border: none; border-radius: 8px; font-family: var(--font-body); font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(184, 134, 11, 0.2);"
				onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(184, 134, 11, 0.3)'"
				onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(184, 134, 11, 0.2)'">
				Sign In
			</button>

			<!-- Toggle Mode -->
			<div style="text-align: center; padding-top: 1rem; border-top: 1px solid rgba(184, 134, 11, 0.3);">
				<p style="color: var(--primary); font-family: var(--font-body); font-size: 0.875rem; margin: 0;">
					<span id="toggleText">Don't have an account?</span>
					<button
						type="button"
						id="toggleModeBtn"
						onclick="toggleAuthMode()"
						style="color: var(--accent); background: none; border: none; cursor: pointer; font-family: var(--font-body); font-weight: 500; transition: color 0.3s ease; margin-left: 0.25rem;"
						onmouseover="this.style.color='var(--primary)'"
						onmouseout="this.style.color='var(--accent)'">
						Sign Up
					</button>
				</p>
			</div>

			<!-- Security Note -->
			<div style="background: rgba(184, 134, 11, 0.1); border: 1px solid rgba(184, 134, 11, 0.3); border-radius: 8px; padding: 0.75rem; margin-top: 0.5rem;">
				<p style="font-size: 0.75rem; color: rgba(184, 134, 11, 0.7); text-align: center; margin: 0;">
					🔒 Your information is secure and encrypted
				</p>
			</div>

			<!-- Error Message Container -->
			<div id="authFormError" style="display: none; background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); border-radius: 8px; padding: 0.75rem;">
				<p style="font-size: 0.875rem; color: var(--destructive); margin: 0; font-family: var(--font-body);"></p>
			</div>
		</form>
	</div>
</div>
