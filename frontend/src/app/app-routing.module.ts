import { NgModule } from '@angular/core';
import { RouterModule, Routes }  from '@angular/router';

const appRoutes = [
  { path: '', redirectTo: '/home', pathMatch: 'full' }
];

@NgModule({
  imports: [
    RouterModule.forRoot(appRoutes)
  ],
  exports: [
    RouterModule
  ]
})

export class AppRoutingModule {}
