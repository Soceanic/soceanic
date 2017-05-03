import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProfileComponent } from './profile/profile.component';
import { ProfieTextPostComponent } from './profie-text-post/profie-text-post.component';
import { ProfileMediaPostComponent } from './profile-media-post/profile-media-post.component';
import { ProfileGridComponent } from './profile-grid/profile-grid.component';
import { ProfilePostFocusComponent } from './profile-post-focus/profile-post-focus.component';

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [ProfileComponent, ProfieTextPostComponent, ProfileMediaPostComponent, ProfileGridComponent, ProfilePostFocusComponent],
  exports: [ProfileComponent, ProfieTextPostComponent, ProfileMediaPostComponent, ProfileGridComponent, ProfilePostFocusComponent]
})
export class ProfileModule { }
