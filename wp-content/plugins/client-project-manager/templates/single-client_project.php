<?php
/**
 * Single Client Project Template
 * 
 * This template can be placed in your active theme's directory
 * to customize the display of individual client projects
 */

get_header(); ?>

<div class="container">
    <main class="site-main">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <!-- Project Meta Information -->
                    <div class="project-details-meta">
                        <h3>Project Details</h3>
                        
                        <?php 
                        $client_name = get_post_meta(get_the_ID(), '_client_name', true);
                        $project_status = get_post_meta(get_the_ID(), '_project_status', true);
                        $project_deadline = get_post_meta(get_the_ID(), '_project_deadline', true);
                        ?>
                        
                        <?php if ($client_name): ?>
                        <div class="meta-row">
                            <span class="meta-label">Client:</span>
                            <span class="meta-value"><?php echo esc_html($client_name); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($project_status): ?>
                        <div class="meta-row">
                            <span class="meta-label">Status:</span>
                            <span class="meta-value">
                                <span class="status-badge status-<?php echo esc_attr($project_status); ?>">
                                    <?php echo esc_html(ucfirst(str_replace('-', ' ', $project_status))); ?>
                                </span>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($project_deadline): ?>
                        <div class="meta-row">
                            <span class="meta-label">Deadline:</span>
                            <span class="meta-value"><?php echo esc_html(date('F j, Y', strtotime($project_deadline))); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="meta-row">
                            <span class="meta-label">Created:</span>
                            <span class="meta-value"><?php echo get_the_date('F j, Y'); ?></span>
                        </div>
                    </div>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'textdomain'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>

                <?php if (get_the_tags()): ?>
                <footer class="entry-footer">
                    <div class="entry-tags">
                        <?php the_tags('<span class="tags-label">Tags:</span> ', ', '); ?>
                    </div>
                </footer>
                <?php endif; ?>
                
                <!-- Navigation to other projects -->
                <nav class="post-navigation">
                    <div class="nav-links">
                        <?php
                        $prev_post = get_previous_post(false, '', 'client_project');
                        $next_post = get_next_post(false, '', 'client_project');
                        
                        if ($prev_post): ?>
                            <div class="nav-previous">
                                <a href="<?php echo get_permalink($prev_post->ID); ?>" rel="prev">
                                    <span class="nav-subtitle">Previous Project</span>
                                    <span class="nav-title"><?php echo get_the_title($prev_post->ID); ?></span>
                                </a>
                            </div>
                        <?php endif;
                        
                        if ($next_post): ?>
                            <div class="nav-next">
                                <a href="<?php echo get_permalink($next_post->ID); ?>" rel="next">
                                    <span class="nav-subtitle">Next Project</span>
                                    <span class="nav-title"><?php echo get_the_title($next_post->ID); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </nav>
                
                <!-- Link back to all projects -->
                <div class="back-to-projects">
                    <a href="<?php echo get_post_type_archive_link('client_project'); ?>" class="back-link">
                        ← Back to All Projects
                    </a>
                </div>
            </article>

        <?php endwhile; ?>
    </main>
</div>

<style>
/* Additional styles for single project template */
.project-details-meta {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
}

.project-details-meta h3 {
    margin-top: 0;
    color: #333;
    border-bottom: 2px solid #0073aa;
    padding-bottom: 10px;
}

.meta-row {
    display: flex;
    margin-bottom: 10px;
    align-items: center;
}

.meta-label {
    font-weight: bold;
    width: 120px;
    color: #333;
}

.meta-value {
    color: #666;
}

.post-navigation {
    margin: 40px 0;
    border-top: 1px solid #