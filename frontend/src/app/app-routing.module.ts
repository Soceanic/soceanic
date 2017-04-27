import { NgModule } from '@angular/core';
import { RouterModule, Routes }  from '@angular/router';

import { FeedModule } from 'app/feed/feed.module';
import { FeedGridComponent } from 'app/feed/feed-grid/feed-grid.component';
import { AuthGuard } from 'app/guards/auth.guard';

const appRoutes = [
  { path: '', redirectTo: '/home', pathMatch: 'full' },
  { path: 'feed', component: FeedGridComponent, canActivate: [AuthGuard] }
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
