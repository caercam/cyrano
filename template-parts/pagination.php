
<?php if ( get_previous_posts_link() || get_next_posts_link() ) : ?>
<?php if ( get_previous_posts_link() ) : ?>
          <a class="nav-previous" href="<?php previous_posts(); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M448 256C448 264.8 440.6 272 431.4 272H54.11l140.7 149.3c6.157 6.531 5.655 16.66-1.118 22.59C190.5 446.6 186.5 448 182.5 448c-4.505 0-9.009-1.75-12.28-5.25l-165.9-176c-5.752-6.094-5.752-15.41 0-21.5l165.9-176c6.19-6.562 16.69-7 23.45-1.094c6.773 5.938 7.275 16.06 1.118 22.59L54.11 240h377.3C440.6 240 448 247.2 448 256z"/></svg>
          </a>
<?php else: ?>
          <span class="nav-previous"></span>
<?php endif; ?>
<?php if ( get_next_posts_link() ) : ?>
          <a class="nav-next" href="<?php next_posts(); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M443.7 266.8l-165.9 176C274.5 446.3 269.1 448 265.5 448c-3.986 0-7.988-1.375-11.16-4.156c-6.773-5.938-7.275-16.06-1.118-22.59L393.9 272H16.59c-9.171 0-16.59-7.155-16.59-15.1S7.421 240 16.59 240h377.3l-140.7-149.3c-6.157-6.531-5.655-16.66 1.118-22.59c6.789-5.906 17.27-5.469 23.45 1.094l165.9 176C449.4 251.3 449.4 260.7 443.7 266.8z"/></svg>
          </a>
<?php else: ?>
          <span class="nav-next"></span>
<?php endif; ?>
<?php endif; ?>