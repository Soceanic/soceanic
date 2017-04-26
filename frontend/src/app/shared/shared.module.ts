import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ModalComponent } from './modal/modal.component';
import { GridComponent } from './grid/grid.component';
import { PostFocusComponent } from './post-focus/post-focus.component';
import { NavComponent } from './nav/nav.component';
import { PostComponent } from './post/post.component';
import { RouterModule } from '@angular/router';

@NgModule({
  imports: [
    CommonModule,
    RouterModule
  ],
  declarations: [ModalComponent, GridComponent, PostFocusComponent, NavComponent, PostComponent],
  exports: [ModalComponent, GridComponent, PostFocusComponent, NavComponent, PostComponent]
})
export class SharedModule { }
