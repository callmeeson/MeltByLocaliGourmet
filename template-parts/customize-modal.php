<?php
/**
 * Customize Modal Template Part - Modern Design
 * 
 * Include this before closing body tag
 */
?>

<!-- Customize Modal -->
<div id="customizeModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(12px); z-index: 9999; padding: 0.5rem; overflow-y: auto; align-items: center; justify-content: center;">
	<div style="background: white; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); max-width: 700px; width: 100%; margin: 0.5rem auto; animation: modalSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
		
		<!-- Modal Header -->
		<div style="background: linear-gradient(135deg, #B8860B 0%, #DAA520 100%); padding: 1rem 1.5rem; border-radius: 12px 12px 0 0; position: relative; overflow: hidden;">
			<!-- Decorative circles -->
			<div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
			<div style="position: absolute; bottom: -20px; left: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
			
			<div style="display: flex; align-items: center; justify-content: space-between; position: relative;">
				<div>
					<h2 style="color: white; font-family: var(--font-serif); font-size: clamp(1.25rem, 2.5vw, 1.5rem); display: flex; align-items: center; gap: 0.4rem; margin: 0; font-weight: 600; letter-spacing: -0.5px;">
						<i data-lucide="cake" style="width: 1.25rem; height: 1.25rem;"></i>
						Customize Your Cake
					</h2>
					<p id="modalProductName" style="color: rgba(255, 255, 255, 0.95); font-family: var(--font-body); margin: 0.35rem 0 0; font-size: 0.875rem; font-weight: 300;"></p>
				</div>
				<button onclick="closeCustomizeModal()" style="color: white; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; padding: 0.5rem; cursor: pointer; transition: all 0.3s ease; width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;"
					onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'; this.style.transform='rotate(90deg)'"
					onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.transform='rotate(0deg)'">
					<i data-lucide="x" style="width: 1rem; height: 1rem;"></i>
				</button>
			</div>
		</div>

		<!-- Product Preview (Hidden Image) -->
		<img id="modalProductImage" src="" alt="" style="display: none;">

		<!-- Modal Content -->
		<div style="padding: 1rem 1.25rem; max-height: 55vh; overflow-y: auto;">
			
			<!-- Size Selection -->
			<div style="margin-bottom: 1.25rem;">
				<label style="display: flex; align-items: center; gap: 0.4rem; color: var(--foreground); font-family: var(--font-body); font-weight: 600; margin-bottom: 0.65rem; font-size: 0.95rem;">
					<div style="width: 5px; height: 5px; background: var(--primary); border-radius: 50%;"></div>
					Cake Size
				</label>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.5rem;">
					<button class="size-option active" onclick="selectSize('Small (6 inch)', this)" style="padding: 0.85rem 0.7rem; border: 2px solid var(--primary); border-radius: 8px; background: linear-gradient(135deg, rgba(184, 134, 11, 0.08) 0%, rgba(218, 165, 32, 0.08) 100%); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; position: relative; overflow: hidden; box-shadow: 0 2px 4px rgba(184, 134, 11, 0.08);"
						onmouseover="if(!this.classList.contains('active')) { this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px -2px rgba(184, 134, 11, 0.15)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(184, 134, 11, 0.08)'; }">
						<div style="font-family: var(--font-serif); color: var(--foreground); font-weight: 600; font-size: 0.95rem; margin-bottom: 0.25rem;">Small</div>
						<div style="font-family: var(--font-body); color: var(--muted-foreground); font-size: 0.7rem; margin-bottom: 0.25rem;">6 inch</div>
						<div style="font-family: var(--font-body); color: var(--primary); font-weight: 600; font-size: 0.8rem;">Base</div>
					</button>
					<button class="size-option" onclick="selectSize('Medium (8 inch)', this)" style="padding: 0.85rem 0.7rem; border: 2px solid var(--border); border-radius: 8px; background: white; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; position: relative; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
						onmouseover="if(!this.classList.contains('active')) { this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px -2px rgba(0, 0, 0, 0.08)'; this.style.borderColor='var(--primary)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'; this.style.borderColor='var(--border)'; }">
						<div style="font-family: var(--font-serif); color: var(--foreground); font-weight: 600; font-size: 0.95rem; margin-bottom: 0.25rem;">Medium</div>
						<div style="font-family: var(--font-body); color: var(--muted-foreground); font-size: 0.7rem; margin-bottom: 0.25rem;">8 inch</div>
						<div style="font-family: var(--font-body); color: var(--primary); font-weight: 600; font-size: 0.8rem;">+50 AED</div>
					</button>
					<button class="size-option" onclick="selectSize('Large (10 inch)', this)" style="padding: 0.85rem 0.7rem; border: 2px solid var(--border); border-radius: 8px; background: white; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; position: relative; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
						onmouseover="if(!this.classList.contains('active')) { this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px -2px rgba(0, 0, 0, 0.08)'; this.style.borderColor='var(--primary)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'; this.style.borderColor='var(--border)'; }">
						<div style="font-family: var(--font-serif); color: var(--foreground); font-weight: 600; font-size: 0.95rem; margin-bottom: 0.25rem;">Large</div>
						<div style="font-family: var(--font-body); color: var(--muted-foreground); font-size: 0.7rem; margin-bottom: 0.25rem;">10 inch</div>
						<div style="font-family: var(--font-body); color: var(--primary); font-weight: 600; font-size: 0.8rem;">+100 AED</div>
					</button>
					<button class="size-option" onclick="selectSize('Extra Large (12 inch)', this)" style="padding: 0.85rem 0.7rem; border: 2px solid var(--border); border-radius: 8px; background: white; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; position: relative; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
						onmouseover="if(!this.classList.contains('active')) { this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px -2px rgba(0, 0, 0, 0.08)'; this.style.borderColor='var(--primary)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'; this.style.borderColor='var(--border)'; }">
						<div style="font-family: var(--font-serif); color: var(--foreground); font-weight: 600; font-size: 0.95rem; margin-bottom: 0.25rem;">XL</div>
						<div style="font-family: var(--font-body); color: var(--muted-foreground); font-size: 0.7rem; margin-bottom: 0.25rem;">12 inch</div>
						<div style="font-family: var(--font-body); color: var(--primary); font-weight: 600; font-size: 0.8rem;">+180 AED</div>
					</button>
				</div>
			</div>

			<!-- Layers -->
			<div style="margin-bottom: 1.25rem;">
				<label style="display: flex; align-items: center; gap: 0.4rem; color: var(--foreground); font-family: var(--font-body); font-weight: 600; margin-bottom: 0.65rem; font-size: 0.95rem;">
					<div style="width: 5px; height: 5px; background: var(--primary); border-radius: 50%;"></div>
					Layers
				</label>
				<div style="display: flex; align-items: center; gap: 1rem; background: var(--secondary); padding: 0.85rem 1.25rem; border-radius: 10px;">
					<button onclick="changeLayers(-1)" style="padding: 0.6rem; background: white; border: 2px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
						onmouseover="this.style.background='var(--primary)'; this.querySelector('i').style.color='white'; this.style.transform='scale(1.05)'; this.style.borderColor='var(--primary)'"
						onmouseout="this.style.background='white'; this.querySelector('i').style.color='var(--primary)'; this.style.transform='scale(1)'; this.style.borderColor='var(--border)'">
						<i data-lucide="minus" style="width: 1rem; height: 1rem; color: var(--primary); transition: color 0.3s ease;"></i>
					</button>
					<div style="flex: 1; text-align: center;">
						<span id="layersCount" style="font-family: var(--font-serif); font-size: 2rem; color: var(--foreground); font-weight: 600;">2</span>
						<div style="font-family: var(--font-body); color: var(--muted-foreground); font-size: 0.7rem; margin-top: 0.25rem;">layers</div>
					</div>
					<button onclick="changeLayers(1)" style="padding: 0.6rem; background: white; border: 2px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
						onmouseover="this.style.background='var(--primary)'; this.querySelector('i').style.color='white'; this.style.transform='scale(1.05)'; this.style.borderColor='var(--primary)'"
						onmouseout="this.style.background='white'; this.querySelector('i').style.color='var(--primary)'; this.style.transform='scale(1)'; this.style.borderColor='var(--border)'">
						<i data-lucide="plus" style="width: 1rem; height: 1rem; color: var(--primary); transition: color 0.3s ease;"></i>
					</button>
					<span id="layerPrice" style="color: var(--primary); font-family: var(--font-body); font-weight: 600; font-size: 0.875rem; min-width: 70px; display: none;"></span>
				</div>
			</div>

			<!-- Flavor, Frosting & Filling -->
			<div style="margin-bottom: 3rem;">
				<label style="display: flex; align-items: center; gap: 0.75rem; color: var(--foreground); font-family: var(--font-body); font-weight: 600; margin-bottom: 1.5rem; font-size: 1.25rem;">
					<div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
					Flavors & Fillings
				</label>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem;">
					<div>
						<label style="display: block; color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Cake Flavor</label>
						<select onchange="updateCustomization('flavor', this.value)" style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); border-radius: 12px; background: white; color: var(--foreground); font-family: var(--font-body); font-size: 1rem; cursor: pointer; transition: all 0.3s ease;"
							onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
							onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
							<option>Vanilla</option>
							<option>Chocolate</option>
							<option>Red Velvet</option>
							<option>Strawberry</option>
							<option>Carrot</option>
							<option>Lemon</option>
							<option>Saffron</option>
							<option>Pistachio</option>
						</select>
					</div>
					
					<div>
						<label style="display: block; color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Frosting Type</label>
						<select onchange="updateCustomization('frosting', this.value)" style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); border-radius: 12px; background: white; color: var(--foreground); font-family: var(--font-body); font-size: 1rem; cursor: pointer; transition: all 0.3s ease;"
							onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
							onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
							<option>Buttercream</option>
							<option>Cream Cheese</option>
							<option>Whipped Cream</option>
							<option>Fondant</option>
							<option>Ganache</option>
							<option>Royal Icing</option>
						</select>
					</div>

					<div>
						<label style="display: block; color: var(--muted-foreground); font-family: var(--font-body); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">Filling</label>
						<select onchange="updateCustomization('filling', this.value)" style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); border-radius: 12px; background: white; color: var(--foreground); font-family: var(--font-body); font-size: 1rem; cursor: pointer; transition: all 0.3s ease;"
							onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
							onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
							<option>Vanilla Cream</option>
							<option>Chocolate Mousse</option>
							<option>Fruit Jam</option>
							<option>Caramel</option>
							<option>Nutella</option>
							<option>Custard</option>
							<option>Fresh Fruit</option>
						</select>
					</div>
				</div>
			</div>

			<!-- Toppings -->
			<div style="margin-bottom: 3rem;">
				<label style="display: flex; align-items: center; gap: 0.75rem; color: var(--foreground); font-family: var(--font-body); font-weight: 600; margin-bottom: 1.5rem; font-size: 1.25rem;">
					<div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
					Premium Toppings
					<span style="font-size: 0.75rem; color: var(--muted-foreground); font-weight: 400; margin-left: 0.5rem;">(Select multiple)</span>
				</label>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
					<button onclick="toggleTopping('Fresh Berries', this)" style="padding: 1.25rem 1.5rem; border: 2px solid var(--border); border-radius: 12px; background: white; text-align: left; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.04);"
						onmouseover="if(!this.classList.contains('active')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; }">
						<div style="color: var(--foreground); font-size: 1rem; font-family: var(--font-body); font-weight: 500; margin-bottom: 0.25rem;">🍓 Fresh Berries</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">+25 AED</div>
					</button>
					<button onclick="toggleTopping('Edible Gold Leaf', this)" style="padding: 1.25rem 1.5rem; border: 2px solid var(--border); border-radius: 12px; background: white; text-align: left; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.04);"
						onmouseover="if(!this.classList.contains('active')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; }">
						<div style="color: var(--foreground); font-size: 1rem; font-family: var(--font-body); font-weight: 500; margin-bottom: 0.25rem;">✨ Edible Gold Leaf</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">+45 AED</div>
					</button>
					<button onclick="toggleTopping('Chocolate Shavings', this)" style="padding: 1.25rem 1.5rem; border: 2px solid var(--border); border-radius: 12px; background: white; text-align: left; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.04);"
						onmouseover="if(!this.classList.contains('active')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; }">
						<div style="color: var(--foreground); font-size: 1rem; font-family: var(--font-body); font-weight: 500; margin-bottom: 0.25rem;">🍫 Chocolate Shavings</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">+20 AED</div>
					</button>
					<button onclick="toggleTopping('Fresh Flowers', this)" style="padding: 1.25rem 1.5rem; border: 2px solid var(--border); border-radius: 12px; background: white; text-align: left; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.04);"
						onmouseover="if(!this.classList.contains('active')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; }">
						<div style="color: var(--foreground); font-size: 1rem; font-family: var(--font-body); font-weight: 500; margin-bottom: 0.25rem;">🌸 Fresh Flowers</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">+35 AED</div>
					</button>
					<button onclick="toggleTopping('Macarons', this)" style="padding: 1.25rem 1.5rem; border: 2px solid var(--border); border-radius: 12px; background: white; text-align: left; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.04);"
						onmouseover="if(!this.classList.contains('active')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; }">
						<div style="color: var(--foreground); font-size: 1rem; font-family: var(--font-body); font-weight: 500; margin-bottom: 0.25rem;">🧁 Macarons</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">+40 AED</div>
					</button>
					<button onclick="toggleTopping('Nuts & Almonds', this)" style="padding: 1.25rem 1.5rem; border: 2px solid var(--border); border-radius: 12px; background: white; text-align: left; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.04);"
						onmouseover="if(!this.classList.contains('active')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; }">
						<div style="color: var(--foreground); font-size: 1rem; font-family: var(--font-body); font-weight: 500; margin-bottom: 0.25rem;">🥜 Nuts & Almonds</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">+15 AED</div>
					</button>
				</div>
			</div>

			<!-- Decoration Style -->
			<div style="margin-bottom: 3rem;">
				<label style="display: flex; align-items: center; gap: 0.75rem; color: var(--foreground); font-family: var(--font-body); font-weight: 600; margin-bottom: 1.5rem; font-size: 1.25rem;">
					<div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
					Decoration Style
				</label>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
					<button class="decoration-option active" onclick="selectDecoration('Simple', this)" style="padding: 1.75rem; border: 2px solid var(--primary); border-radius: 16px; background: linear-gradient(135deg, rgba(184, 134, 11, 0.08) 0%, rgba(218, 165, 32, 0.08) 100%); transition: all 0.3s ease; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(184, 134, 11, 0.1); text-align: center;">
						<div style="font-size: 2rem; margin-bottom: 0.75rem;">🎨</div>
						<div style="color: var(--foreground); font-size: 1.125rem; font-family: var(--font-serif); font-weight: 600; margin-bottom: 0.5rem;">Simple</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">Included</div>
					</button>
					<button class="decoration-option" onclick="selectDecoration('Elegant Piping', this)" style="padding: 1.75rem; border: 2px solid var(--border); border-radius: 16px; background: white; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.04); text-align: center;"
						onmouseover="if(!this.classList.contains('active')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px -4px rgba(0, 0, 0, 0.1)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; }">
						<div style="font-size: 2rem; margin-bottom: 0.75rem;">🌟</div>
						<div style="color: var(--foreground); font-size: 1.125rem; font-family: var(--font-serif); font-weight: 600; margin-bottom: 0.5rem;">Elegant Piping</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">+50 AED</div>
					</button>
					<button class="decoration-option" onclick="selectDecoration('Custom Design', this)" style="padding: 1.75rem; border: 2px solid var(--border); border-radius: 16px; background: white; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.04); text-align: center;"
						onmouseover="if(!this.classList.contains('active')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px -4px rgba(0, 0, 0, 0.1)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; }">
						<div style="font-size: 2rem; margin-bottom: 0.75rem;">💎</div>
						<div style="color: var(--foreground); font-size: 1.125rem; font-family: var(--font-serif); font-weight: 600; margin-bottom: 0.5rem;">Custom Design</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">+100 AED</div>
					</button>
					<button class="decoration-option" onclick="selectDecoration('Premium 3D Design', this)" style="padding: 1.75rem; border: 2px solid var(--border); border-radius: 16px; background: white; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.04); text-align: center;"
						onmouseover="if(!this.classList.contains('active')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px -4px rgba(0, 0, 0, 0.1)'; }"
						onmouseout="if(!this.classList.contains('active')) { this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)'; }">
						<div style="font-size: 2rem; margin-bottom: 0.75rem;">👑</div>
						<div style="color: var(--foreground); font-size: 1.125rem; font-family: var(--font-serif); font-weight: 600; margin-bottom: 0.5rem;">Premium 3D</div>
						<div style="color: var(--primary); font-size: 0.875rem; font-weight: 600;">+200 AED</div>
					</button>
				</div>
			</div>

			<!-- Custom Message & Delivery Date -->
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
				<div>
					<label style="display: flex; align-items: center; gap: 0.5rem; color: var(--foreground); font-family: var(--font-body); font-weight: 600; margin-bottom: 1rem; font-size: 1.125rem;">
						<i data-lucide="message-square" style="width: 1.25rem; height: 1.25rem; color: var(--primary);"></i>
						Custom Message
					</label>
					<input type="text" onkeyup="updateCharCount('customMessage', 50, this)" onchange="updateCustomization('customMessage', this.value)" placeholder="e.g., Happy Birthday Sarah!" maxlength="50" style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); border-radius: 12px; background: white; color: var(--foreground); font-family: var(--font-body); font-size: 1rem; transition: all 0.3s ease;"
						onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
						onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
					<p style="font-size: 0.75rem; color: var(--muted-foreground); margin-top: 0.5rem;"><span id="customMessageCount">0</span>/50 characters</p>
				</div>

				<div>
					<label style="display: flex; align-items: center; gap: 0.5rem; color: var(--foreground); font-family: var(--font-body); font-weight: 600; margin-bottom: 1rem; font-size: 1.125rem;">
						<i data-lucide="calendar" style="width: 1.25rem; height: 1.25rem; color: var(--primary);"></i>
						Delivery Date
					</label>
					<input type="date" id="deliveryDate" onchange="updateCustomization('deliveryDate', this.value)" style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); border-radius: 12px; background: white; color: var(--foreground); font-family: var(--font-body); font-size: 1rem; cursor: pointer; transition: all 0.3s ease;"
						onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
						onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
				</div>
			</div>

			<!-- Special Instructions -->
			<div>
				<label style="display: flex; align-items: center; gap: 0.75rem; color: var(--foreground); font-family: var(--font-body); font-weight: 600; margin-bottom: 1rem; font-size: 1.125rem;">
					<div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
					Special Instructions
				</label>
				<textarea onkeyup="updateCharCount('specialInstructions', 500, this)" onchange="updateCustomization('specialInstructions', this.value)" placeholder="Any dietary restrictions, allergies, or special requests..." maxlength="500" style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); border-radius: 12px; background: white; color: var(--foreground); font-family: var(--font-body); font-size: 1rem; min-height: 120px; resize: vertical; transition: all 0.3s ease;"
					onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(184, 134, 11, 0.1)'"
					onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"></textarea>
				<p style="font-size: 0.75rem; color: var(--muted-foreground); margin-top: 0.5rem;"><span id="specialInstructionsCount">0</span>/500 characters</p>
			</div>
		</div>

		<!-- Modal Footer with Price -->
		<div style="position: sticky; bottom: 0; background: linear-gradient(to top, white 0%, white 90%, rgba(255,255,255,0) 100%); border-top: 1px solid var(--border); padding: 1.5rem 2rem; border-radius: 0 0 16px 16px;">
			<div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap;">
				<div style="flex: 1; min-width: 200px;">
					<div style="color: var(--muted-foreground); font-size: 0.75rem; font-family: var(--font-body); font-weight: 500; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Price</div>
					<div style="font-size: 2.25rem; color: var(--primary); font-family: var(--font-serif); font-weight: 700; line-height: 1;">
						<span id="totalPrice">350.00</span> <span style="font-size: 1.5rem;">AED</span>
					</div>
					<div id="priceBreakdown" style="display: none; font-size: 0.8rem; color: var(--muted-foreground); font-family: var(--font-body); margin-top: 0.5rem;">
						Base: <span id="basePrice" style="font-weight: 600;">350.00</span> AED + Customization: <span id="additionalPrice" style="font-weight: 600; color: var(--primary);">0.00</span> AED
					</div>
				</div>
				<button onclick="addCustomizedToCart(event)" style="padding: 1rem 2.5rem; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); color: white; border: none; border-radius: 12px; font-family: var(--font-body); font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 25px -5px rgba(184, 134, 11, 0.4); min-width: 180px; position: relative; overflow: hidden;"
					onmouseover="this.style.transform='translateY(-2px) scale(1.02)'; this.style.boxShadow='0 20px 35px -5px rgba(184, 134, 11, 0.5)';"
					onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 25px -5px rgba(184, 134, 11, 0.4)';">
					<span style="position: relative; z-index: 1;">Add to Cart</span>
				</button>
			</div>
		</div>
	</div>
</div>

<style>
@keyframes modalSlideIn {
	from {
		opacity: 0;
		transform: translateY(20px) scale(0.98);
	}
	to {
		opacity: 1;
		transform: translateY(0) scale(1);
	}
}

.size-option.active,
.decoration-option.active,
button.active {
	background: linear-gradient(135deg, rgba(184, 134, 11, 0.12) 0%, rgba(218, 165, 32, 0.12) 100%) !important;
	border-color: var(--primary) !important;
	box-shadow: 0 8px 16px -2px rgba(184, 134, 11, 0.2) !important;
	transform: translateY(-4px) !important;
}

/* Scrollbar styling */
#customizeModal > div > div:nth-child(3)::-webkit-scrollbar {
	width: 8px;
}

#customizeModal > div > div:nth-child(3)::-webkit-scrollbar-track {
	background: #f1f1f1;
	border-radius: 10px;
}

#customizeModal > div > div:nth-child(3)::-webkit-scrollbar-thumb {
	background: var(--primary);
	border-radius: 10px;
}

#customizeModal > div > div:nth-child(3)::-webkit-scrollbar-thumb:hover {
	background: var(--accent);
}
</style>

<script src="<?php echo get_template_directory_uri(); ?>/js/customize-modal.js"></script>

