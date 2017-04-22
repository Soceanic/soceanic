import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NewPostModalComponent } from './new-post-modal/new-post-modal.component';
import { FeedPostComponent } from './feed-post/feed-post.component';
import { FeedGridComponent } from './feed-grid/feed-grid.component';
import { FeedPostFocusComponent } from './feed-post-focus/feed-post-focus.component';

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [NewPostModalComponent, FeedPostComponent, FeedGridComponent, FeedPostFocusComponent]
})
export class FeedModule { }
