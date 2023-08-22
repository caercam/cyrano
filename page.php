<?php get_header(); ?>
      <article id="<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-thumbnail">
          <?php the_post_thumbnail( 'original' ); ?>
        </div>
        <div class="post-title">
          <?php the_title(); ?>
        </div>
        <div class="post-meta">
          <span class="post-date" title="<?php echo esc_attr( get_the_date( 'j F Y à H:i:s' ) ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M64 264C64 241.9 81.91 224 104 224H184C206.1 224 224 241.9 224 264V344C224 366.1 206.1 384 184 384H104C81.91 384 64 366.1 64 344V264zM96 264V344C96 348.4 99.58 352 104 352H184C188.4 352 192 348.4 192 344V264C192 259.6 188.4 256 184 256H104C99.58 256 96 259.6 96 264zM128 64H320V16C320 7.164 327.2 0 336 0C344.8 0 352 7.164 352 16V64H384C419.3 64 448 92.65 448 128V448C448 483.3 419.3 512 384 512H64C28.65 512 0 483.3 0 448V128C0 92.65 28.65 64 64 64H96V16C96 7.164 103.2 0 112 0C120.8 0 128 7.164 128 16V64zM32 448C32 465.7 46.33 480 64 480H384C401.7 480 416 465.7 416 448V192H32V448zM32 128V160H416V128C416 110.3 401.7 96 384 96H64C46.33 96 32 110.3 32 128z"/></svg>
            <?php echo 'il y a ' . human_time_diff( get_the_date( 'U' ), current_time( 'timestamp' ) ); ?>
          </span>
<?php if ( current_user_can( 'edit_post', get_the_ID() ) ) : ?>
          <a class="post-edit" href="<?php echo esc_url( get_edit_post_link() ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M386.7 22.63C411.7-2.365 452.3-2.365 477.3 22.63L489.4 34.74C514.4 59.74 514.4 100.3 489.4 125.3L269 345.6C260.6 354.1 249.9 359.1 238.2 362.7L147.6 383.6C142.2 384.8 136.6 383.2 132.7 379.3C128.8 375.4 127.2 369.8 128.4 364.4L149.3 273.8C152 262.1 157.9 251.4 166.4 242.1L386.7 22.63zM454.6 45.26C442.1 32.76 421.9 32.76 409.4 45.26L382.6 72L440 129.4L466.7 102.6C479.2 90.13 479.2 69.87 466.7 57.37L454.6 45.26zM180.5 281L165.3 346.7L230.1 331.5C236.8 330.2 242.2 327.2 246.4 322.1L417.4 152L360 94.63L189 265.6C184.8 269.8 181.8 275.2 180.5 281V281zM208 64C216.8 64 224 71.16 224 80C224 88.84 216.8 96 208 96H80C53.49 96 32 117.5 32 144V432C32 458.5 53.49 480 80 480H368C394.5 480 416 458.5 416 432V304C416 295.2 423.2 288 432 288C440.8 288 448 295.2 448 304V432C448 476.2 412.2 512 368 512H80C35.82 512 0 476.2 0 432V144C0 99.82 35.82 64 80 64H208z"/></svg>
            Modifier
          </a>
<?php endif; ?>
        </div>
        <div class="post-content">
          <?php the_content(); ?>
        </div>
      </article>
<?php get_footer(); ?>
