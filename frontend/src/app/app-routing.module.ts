import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {DatatableComponent} from "./datatable/datatable.component";
import {ListPageComponent} from "./list-page/list-page.component";

const routes: Routes = [
  {
    path:  '**',
    component:  ListPageComponent,
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
