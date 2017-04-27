import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { GroupsComponent } from './groups/groups.component';
import { GroupComponent } from './group/group.component';

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [GroupsComponent, GroupComponent]
})
export class GroupModule { }
