<?php
/**
 * Template Name: Custom Search Page
 *
 * @package Melt_Custom
 */

get_header();
?>

<style>
    /* Hide browser default search clear button */
    input[type="search"]::-webkit-search-decoration,
    input[type="search"]::-webkit-search-cancel-button,
    input[type="search"]::-webkit-search-results-button,
    input[type="search"]::-webkit-search-results-decoration {
        -webkit-appearance: none;
        display: none;
    }
</style>

<div class="section">
    <div class="section-container" style="max-width: 800px; margin: 0 auto; padding: 6rem 2rem; min-height: 60vh;">
        
        <header class="page-header" style="text-align: center; margin-bottom: 4rem;">
            <h1 class="page-title" style="font-family: var(--font-serif); font-size: clamp(2.5rem, 5vw, 4rem); margin-bottom: 1.5rem; color: var(--foreground);">
                <?php esc_html_e( 'Search', 'melt-custom' ); ?>
            </h1>
            <p style="font-family: var(--font-body); color: var(--muted-foreground); font-size: 1.1rem;">
                <?php esc_html_e( 'Find your favorite cakes and pastries', 'melt-custom' ); ?>
            </p>
        </header>

        <!-- Large Search Form -->
        <form role="search" method="get" class="search-form-large" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="position: relative; margin-bottom: 4rem;">
            <input type="search" class="search-field-large" 
                   placeholder="<?php echo esc_attr_x( 'Type here to search...', 'placeholder', 'melt-custom' ); ?>" 
                   value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" 
                   style="width: 100%; padding: 1.5rem 4rem 1.5rem 2rem; font-size: 1.25rem; border: 2px solid var(--border); border-radius: 50px; outline: none; transition: all 0.3s ease; font-family: var(--font-body);"
                   onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(184, 134, 11, 0.1)';"
                   onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';"
            />
            
            <!-- Clear Button (X) -->
            <button type="button" class="search-clear-large" 
                    style="position: absolute; right: 4.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--muted-foreground); cursor: pointer; display: none;"
                    onclick="document.querySelector('.search-field-large').value = ''; document.querySelector('.search-field-large').focus(); this.style.display = 'none';">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>

            <!-- Submit Button (Arrow) -->
            <button type="submit" class="search-submit-large" style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--primary); cursor: pointer;">
                <i data-lucide="arrow-right" style="width: 24px; height: 24px;"></i>
            </button>
        </form>

        <script>
            // Handle X button visibility
            const searchInput = document.querySelector('.search-field-large');
            const clearBtn = document.querySelector('.search-clear-large');
            
            if (searchInput && clearBtn) {
                searchInput.addEventListener('input', function() {
                    clearBtn.style.display = this.value.length > 0 ? 'block' : 'none';
                });
                // Check on load too
                if (searchInput.value.length > 0) clearBtn.style.display = 'block';
            }
        </script>

        <!-- Popular Categories -->
        <div class="search-categories" style="text-align: center;">
            <h3 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 2rem; color: var(--foreground);">
                <?php esc_html_e( 'Popular Categories', 'melt-custom' ); ?>
            </h3>
            
            <div class="category-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem;">
                <?php
                // Try to get WooCommerce product categories first
                $categories = array();
                
                if ( class_exists( 'WooCommerce' ) ) {
                    $product_categories = get_terms( array(
                        'taxonomy'   => 'product_cat',
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                        'hide_empty' => true,
                        'number'     => 6
                    ) );
                    
                    if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
                        foreach ( $product_categories as $cat ) {
                            // Exclude 'Uncategorized'
                            if ( $cat->slug === 'uncategorized' ) continue;
                            
                            $categories[ $cat->name ] = get_term_link( $cat );
                        }
                    }
                }
                
                // Fallback to Blog Categories if no product categories or WC not active
                if ( empty( $categories ) ) {
                    $blog_categories = get_categories( array(
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                        'number'     => 6,
                        'exclude'    => array( 1 ) // Exclude Uncategorized
                    ) );
                    
                    foreach ( $blog_categories as $cat ) {
                        $categories[ $cat->name ] = get_category_link( $cat->term_id );
                    }
                }
                
                // Fallback hardcoded if absolutely nothing exists
                if ( empty( $categories ) ) {
                    $categories = array(
                        'Birthday Cakes' => '/?s=birthday',
                        'Wedding Cakes' => '/?s=wedding',
                        'Chocolates' => '/?s=chocolate',
                        'Pastries' => '/?s=pastry',
                        'Gift Boxes' => '/?s=gift',
                        'Vegan' => '/?s=vegan'
                    );
                }

                foreach ($categories as $name => $link) :
                ?>
                    <a href="<?php echo esc_url( $link ); ?>" class="category-card" 
                       style="display: block; padding: 2rem 1rem; background: #fff; border: 1px solid var(--border); border-radius: 12px; text-decoration: none; color: var(--foreground); transition: all 0.3s ease;"
                       onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.05)';"
                       onmouseout="this.style.borderColor='var(--border)'; this.style.transform='none'; this.style.boxShadow='none';">
                        <span style="font-family: var(--font-body); font-weight: 500; font-size: 1rem;"><?php echo esc_html( $name ); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<script>
    // Auto-focus search input on load
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.querySelector('.search-field-large');
        if (input) input.focus();
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

<?php get_footer(); ?>
