import { NgModule } from '@angular/core';
import { RouterModule, Routes }  from '@angular/router';

import { FeedModule } from 'app/feed/feed.module';
import { FeedGridComponent } from 'app/feed/feed-grid/feed-grid.component';

const appRoutes = [
  { path: '', redirectTo: '/home', pathMatch: 'full' },
  { path: 'feed', component: FeedGridComponent }
];

@NgModule({
  imports: [
    RouterModule.forRoot(appRoutes),
    FeedModule
  ],
  exports: [
    RouterModule
  ]
})

export class AppRoutingModule {}
