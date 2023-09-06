
          <form action="<?php echo esc_url( home_url() ); ?>" method="GET">
            <input type="search" name="s" placeholder="Rechercher…" value="<?php echo isset( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : ''; ?>" />
            <input type="submit" value="Rechercher" />
          </form>
