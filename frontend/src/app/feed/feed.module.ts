import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NewPostModalComponent } from './new-post-modal/new-post-modal.component';
import { FeedPostComponent } from './feed-post/feed-post.component';
import { FeedGridComponent } from './feed-grid/feed-grid.component';
import { FeedPostFocusComponent } from './feed-post-focus/feed-post-focus.component';
import { HttpModule } from '@angular/http';

import { FeedService } from 'app/services/feed.service';
import { Post } from 'app/services/objects/post';

@NgModule({
  imports: [
    CommonModule,
    HttpModule
  ],
  declarations: [NewPostModalComponent, FeedPostComponent, FeedGridComponent, FeedPostFocusComponent],
  providers: [FeedService, Post],
  exports: [NewPostModalComponent, FeedPostComponent, FeedGridComponent, FeedPostFocusComponent]
})
export class FeedModule { }
