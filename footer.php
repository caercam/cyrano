
      <nav class="pagination">
<?php get_template_part( 'template-parts/pagination' ); ?>
      </nav>
    </main>
    <footer class="footer">
      <div class="widgets-area">
        <?php dynamic_sidebar( 'sidebar-footer' ); ?>
      </div>
      <div class="legal">
        <p>&copy;2009-<?php echo date('Y'); ?> − Charlie Merland</p>
      </div>
    </footer>

  </div>

<?php wp_footer(); ?>

  </body>
</html>
