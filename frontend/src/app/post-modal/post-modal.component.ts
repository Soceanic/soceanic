import { Component, OnInit } from '@angular/core';
import { Post } from '../api/feed/post';
import { FeedService } from '../api/feed/feed.service';

@Component({
  selector: 'app-post-modal',
  template: `
  <div (click)="onContainerClicked($event)" class="modal fade" tabindex="-1" [ngClass]="{'in': visibleAnimate}"
       [ngStyle]="{'display': visible ? 'block' : 'none', 'opacity': visibleAnimate ? 1 : 0}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <ng-content select=".app-modal-header"></ng-content>
        </div>
        <div class="modal-body">
          <ng-content select=".app-modal-body"></ng-content>
        </div>
        <div class="modal-footer">
          <ng-content select=".app-modal-footer"></ng-content>
        </div>
      </div>
    </div>
  </div>
  `,
  styles: [`
    .modal {
      background: rgba(0,0,0,0.6);
    }
  `]
})

export class PostModalComponent implements OnInit{

  currentUser: string = 'borisirl';

  public visible = false;
  private visibleAnimate = false;

  newPost: Post;

  constructor(private service: FeedService) { }

  ngOnInit(){
    this.newPost = new Post();
  }

  public show(): void {
    this.visible = true;
    setTimeout(() => this.visibleAnimate = true, 100);
  }

  public hide(): void {
    this.visibleAnimate = false;
    setTimeout(() => this.visible = false, 300);
  }

  public onContainerClicked(event: MouseEvent): void {
    if ((<HTMLElement>event.target).classList.contains('modal')) {
      this.hide();
    }
  }

  public post(){
    this.newPost.username = this.currentUser;
    this.newPost.post_id = 9999;
    this.newPost.likes = 0;
    this.newPost.attachment = 'lol what attachment';
    this.service.add(this.newPost);
    this.hide();
  }

}
